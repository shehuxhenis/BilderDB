<?php

require_once '../repository/GalleryRepository.php';
require_once '../repository/PictureRepository.php';
require_once 'GalleryController.php';

class pictureController
{
    public function index()
    {
        $view = new View('gallery_index');
        $view->display();
    }

    function createThumbnail($filename) {

        if(!empty($_SESSION['id'])) {
            $gallery_id = $_SESSION['id'];
        } else {
            $gallery_id = 0;
            echo "Es wurde keine Galerie ausgewählt.";
        }

        if(preg_match('/[.](jpg)$/', $filename)) {
            $im = imagecreatefromjpeg('data/'.$gallery_id.'/' . $filename);
        } else if (preg_match('/[.](gif)$/', $filename)) {
            $im = imagecreatefromgif('data/'.$gallery_id.'/' . $filename);
        } else if (preg_match('/[.](png)$/', $filename)) {
            $im = imagecreatefrompng('data/'.$gallery_id.'/' . $filename);
        }

        $ox = imagesx($im);
        $oy = imagesy($im);

        $nx = 250;
        $ny = floor($oy * ($nx / $ox));

        $nm = imagecreatetruecolor($nx, $ny);

        imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);

        if(!file_exists('data/'.$gallery_id.'/thumbs')) {
            if(!mkdir('data/'.$gallery_id.'/thumbs')) {
                die("There was a problem. Please try again!");
            }
        }

        imagejpeg($nm, 'data/'.$gallery_id.'/thumbs/' . $filename);
    }

    public function upload(){

        if(isset($_FILES['image'])){

            $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
            $file_name = $this->generateRandomString() . "." . $file_ext;
            $file_size = $_FILES['image']['size'];
            $file_tmp = $_FILES['image']['tmp_name'];

            $extension= array("jpeg","jpg","png", "gif");

            $title = htmlentities($_POST['title']);
            $desc = htmlentities($_POST['desc']);

            $pictureRepository = new PictureRepository();

            if(!empty($_SESSION['id'])) $gallery_id = $_SESSION['id'];


            if(in_array($file_ext,$extension)=== false){
                $error = "extension not allowed, please choose a JPEG or PNG file.";
            } else if($file_size > 4194304) {
                $error = "File size must be less than 4 MB";
            } else {
                $error = "true";
            }

            if($error == "true") {
                move_uploaded_file($file_tmp,"data/".$gallery_id."/".$file_name);

                if($pictureRepository->upload($gallery_id, $file_name, $title, $desc)) {
                    header('Location: /picture/getPictures?id='. $_GET['id']);
                }

                if (file_exists('data/$file_name') == false ) {
                    $this->createThumbnail($file_name);
                }

            } else {
                echo '<p>$error</p>';
            }
        }
    }

    public function delete() {
        if(!empty($_SESSION['id'])) $gallery_id = $_SESSION['id'];

        $pictureRepository = new PictureRepository();
        $bildname = $pictureRepository->getPictureNameById($_GET['bildId']);
        if ($pictureRepository->deletePicture($_GET['bildId'])) {
            unlink("data/". $gallery_id ."/". $bildname->filename);
            unlink("data/". $gallery_id ."/thumbs/". $bildname->filename);
        }
        header("Location: /picture/getPictures?id=" . $gallery_id);


    }

    public function getPictures() {
        $galleryController = new GalleryController();
        if (!isset($_SESSION['user'])) {
            $view = new View('public_gallery_index');
            $galleryRepository = new GalleryRepository();
            $view->bilder = $galleryRepository->getPictures($_GET['id']);
            $view->display();
        } else if (isset($_SESSION['user']) && $_SESSION['user']->admin) {
            if ($galleryController->checkGallery($_SESSION['user']->id)) {
                $view = new View('gallery_index');
                $_SESSION['id'] = $_GET['id'];
                $galleryRepository = new GalleryRepository();
                $view->bilder = $galleryRepository->getPictures($_GET['id']);
                $view->display();
            } else {
                $view = new View('default_error');
                $view->title = "Error: Zugriff auf fremde Galerie!";
                $view->display();
            }
        } else {
            $view = new View('default_error');
            $view->title = "Error: Zugriff auf fremde Galerie!";
            $view->display();
        }


    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function notFound() {
        $view = new View('default_error');
        $view->title = "Die aufgerufene Seite wurde nicht gefunden.";
        $view->display();
    }
}