<?php

require_once '../repository/UserRepository.php';
require_once '../repository/GalleryRepository.php';

class userController
{
    public function index()
    {
        $view = new View('user_index');
        $view->display();
    }

    public function registration()
    {
        $view = new View('login_registration');
        $view->display();
    }

    public function login()
    {
        $view = new View('login_index');
        $view->display();
    }

    public function doLogin()
    {
        if (isset($_POST['login'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $userRepository = new UserRepository();

            if ($userRepository->login($email, $password)) {
                $view = new View('default_index');
                $view->display();
            } else {
                $this->login();
            }
        }
    }

    public function doRegistration()
    {

        if (isset($_POST['reg'])) {
            $email = $_POST['email'];
            $username = htmlentities($_POST['username']);
            $password = $_POST['password'];
            $passwordrep = $_POST['passwordrep'];
            $userRepository = new UserRepository();

            if ($password != $passwordrep) {
                echo 'Passwords do not match.';
            } else {
                $userRepository->create($email, $username, $password);
                $userRepository->login($email, $password);
                header("Location: /");
            }


        }
    }

    public function doLogout()
    {
        unset($_SESSION['user']);
        session_destroy();
        $this->login();
    }

    public function confirmDelete() {
        $view = new View('confirm_delete');
        $view->display();
    }

    public function doDelete(){
        $userRepository = new UserRepository();
        $galleryRepository = new GalleryRepository();

        $result = $galleryRepository->getGalleriesByUserId($_SESSION['user']->id);
        $a = array();

        while ($entry = $result->fetch_object()){
            $a[] = $entry;
        }

        foreach ($a as $gallery) {

            $dir = ("data/" . $gallery->id . "/thumbs");
            $dirContents = scandir($dir);

            unset($dirContents[0], $dirContents[1]);
            foreach ($dirContents as $file) {
                unlink($dir . "/" . $file);
            }

            $dir = ("data/" . $gallery->id);
            $dirContents = scandir($dir);

            unset($dirContents[0], $dirContents[1]);
            foreach ($dirContents as $file) {
                if (!is_dir($dir . "/" . $file))
                    unlink($dir . "/" . $file);
            }

            if (file_exists("data/" . $gallery->id . "/thumbs")) {
                rmdir("data/" . $gallery->id . "/thumbs");
            }
            rmdir("data/" . $gallery->id);
        }

        if ($userRepository->deleteUser($_SESSION['user']->id)) {
            $this->doLogout();
        } else {
            echo "Etwas ist schief gegangen...";
        }

    }

    public function doAlter()
    {
        $userRepository = new UserRepository();
        $email = $_SESSION['user']->email;

        if (isset($_POST['alterusrnm'])) {
            $username = htmlentities($_POST['username']);
            $userRepository->alterUserName($username, $email);
            $_SESSION['name'] = $username;

            header('Location: /');

        } else {
            if ($_POST['password'] == $_POST['passwordrep']) {

                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                $userRepository->alterUserPassword($password, $email);

                header('Location: /');

            } else {
                echo 'Passwords do not match';
            }

        }
    }

    public function getUsers() {
        $userRepository = new UserRepository();

        $a = array();

        $result = $userRepository->getUsers();

        while ($entry = $result->fetch_object()){
            $a[] = $entry;
        }

        $view = new View('user_index');
        $view->users = $a;
        $view->display();
    }

    function doDeleteByAdmin() {
        $userRepository = new UserRepository();
        $galleryRepository = new GalleryRepository();

        $result = $galleryRepository->getGalleriesByUserId($_GET['id']);
        $a = array();

        while ($entry = $result->fetch_object()){
            $a[] = $entry;
        }

        foreach ($a as $gallery) {

            $dir = ("data/" . $gallery->id . "/thumbs");
            $dirContents = scandir($dir);

            unset($dirContents[0], $dirContents[1]);
            foreach ($dirContents as $file) {
                unlink($dir . "/" . $file);
            }

            $dir = ("data/" . $gallery->id);
            $dirContents = scandir($dir);

            unset($dirContents[0], $dirContents[1]);
            foreach ($dirContents as $file) {
                if (!is_dir($dir . "/" . $file))
                    unlink($dir . "/" . $file);
            }

            if (file_exists("data/" . $gallery->id . "/thumbs")) {
                rmdir("data/" . $gallery->id . "/thumbs");
            }
            rmdir("data/" . $gallery->id);
        }

        if ($userRepository->deleteUser($_GET['id'])) {
            header("Location: /user/getUsers");

        } else {
            echo "Etwas ist schief gegangen...";
        }
    }

    public function notFound() {
        $view = new View('default_error');
        $view->title = "Die aufgerufene Seite wurde nicht gefunden.";
        $view->display();
    }
}