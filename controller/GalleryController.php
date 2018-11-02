<?php

require_once '../repository/GalleryRepository.php';

class GalleryController
{

    public function index()
    {
        $galleryRepository = new GalleryRepository();

        $a = array();

        $result = $galleryRepository->getAllGalleries();

        while ($entry = $result->fetch_object()){
            if ($_SESSION['user']->id == $entry->user_id){
                $a[] = $entry;
            }
        }

        $_SESSION['galleries'] = $a;

        $view = new View('default_index');
        $view->display();
    }

    public function doDelete() {
        $galleryRepository = new GalleryRepository();
        $galleryId = $_GET['id'];

        $dir = ("data/" . $galleryId . "/thumbs");
        $dirContents = scandir($dir);

        unset($dirContents[0], $dirContents[1]);
        foreach ($dirContents as $file) {
            unlink($dir . "/" . $file);
        }

        $dir = ("data/" . $galleryId);
        $dirContents = scandir($dir);

        unset($dirContents[0], $dirContents[1]);
        foreach ($dirContents as $file) {
            if (!is_dir($dir . "/" . $file)) {
                unlink($dir . "/" . $file);
            }
        }

        if (file_exists("data/" . $galleryId . "/thumbs")) {
            rmdir("data/" . $galleryId . "/thumbs");
        }
        rmdir("data/" . $galleryId);

        $galleryRepository->deleteGalleryById($galleryId);
        header("Location: /");
    }

    public function checkGallery($userId)
    {
        $galleryRepository = new GalleryRepository();
        $galleryUserIds = $galleryRepository->getUserIdByGalleryId($_GET['id']);
        foreach ($galleryUserIds as $uid) {
            $galleryUid = $uid['user_id'];
            break;
        }
        if ($_SESSION['user']->admin || $userId == $galleryUid) {
            return true;
        } else {
            return false;
        }
    }

    public function getPublicGalleries() {
        $galleryRepository = new GalleryRepository();

        $a = array();

        $result = $galleryRepository->getPublicGalleries();

        while ($entry = $result->fetch_object()){
            $a[] = $entry;
        }

        $view = new View('public_default_index');
        $view->publicGalleries = $a;
        $view->display();
    }

    public function notFound() {
        $view = new View('default_error');
        $view->title = "Die aufgerufene Seite wurde nicht gefunden.";
        $view->display();
    }

}

