<?php

require_once 'GalleryController.php';
require_once '../repository/GalleryRepository.php';

/**
 * Der Controller ist der Ort an dem es für jede Seite, welche der Benutzer
 * anfordern kann eine Methode gibt, welche die dazugehörende Businesslogik
 * beherbergt.
 *
 * Welche Controller und Funktionen muss ich erstellen?
 *   Es macht sinn, zusammengehörende Funktionen (z.B: User anzeigen, erstellen,
 *   bearbeiten & löschen) gemeinsam in einem passend benannten Controller (z.B:
 *   UserController) zu implementieren. Nicht zusammengehörende Features sollten
 *   jeweils auf unterschiedliche Controller aufgeteilt werden.
 *
 * Was passiert in einer Controllerfunktion?
 *   Die Anforderungen an die einzelnen Funktionen sind sehr unterschiedlich.
 *   Folgend die gängigsten:
 *     - Dafür sorgen, dass dem Benutzer eine View (HTML, CSS & JavaScript)
 *         gesendet wird.
 *     - Daten von einem Model (Verbindungsstück zur Datenbank) anfordern und
 *         der View übergeben, damit diese Daten dann für den Benutzer in HTML
 *         Code umgewandelt werden können.
 *     - Daten welche z.B. von einem Formular kommen validieren und dem Model
 *         übergeben, damit sie in der Datenbank persistiert werden können.
 */
class DefaultController
{
    /**
     * Die index Funktion des DefaultControllers sollte in jedem Projekt
     * existieren, da diese ausgeführt wird, falls die URI des Requests leer
     * ist. (z.B. http://my-project.local/). Weshalb das so ist, ist und wann
     * welcher Controller und welche Methode aufgerufen wird, ist im Dispatcher
     * beschrieben.
     */
    public function index()
    {
        // In diesem Fall möchten wir dem Benutzer die View mit dem Namen
        //   "default_index" rendern. Wie das genau funktioniert, ist in der
        //   View Klasse beschrieben.

        if (!empty($_SESSION['user'])) {
            if ($_SESSION['user']->admin) {
                $galleryRepository = new GalleryRepository();

                $a = array();

                $result = $galleryRepository->getAllGalleries();

                while ($entry = $result->fetch_object()) {
                    $a[] = $entry;
                }

                $_SESSION['galleries'] = $a;

                $view = new View('default_index');
                $view->display();
            } else if (isset($_SESSION['user'])) {
                $galleryRepository = new GalleryRepository();

                $a = array();

                $result = $galleryRepository->getAllGalleries();

                while ($entry = $result->fetch_object()) {
                    if ($_SESSION['user']->id == $entry->user_id) {
                        $a[] = $entry;
                    }
                }

                $_SESSION['galleries'] = $a;

                $view = new View('default_index');
                $view->display();
            }
        } else {
            $galleryController = new GalleryController();
            $galleryController->getPublicGalleries();
        }
    }

    public function create(){
        if(isset($_SESSION['user'])) {
            $view = new View('gallery_create');
            $view->display();
        } else {
            $view = new View('default_error');
            $view->title = "Bitte Melden sie sich an.";
            $view->display();
        }
    }

    public function doCreate(){

        if (isset($_POST['gal'])){

            $user_id = $_SESSION['user']->id;
            $name = htmlentities($_POST['name']);
            $desc = htmlentities($_POST['desc']);
            $public = 0;
            if(isset($_POST['public'])) {
                $public = 1;
            }

            $galleryRepository = new GalleryRepository();

            if ($galleryRepository->create($user_id, $name, $desc, $public)) {
                try {
                    $this->createDir();
                    header("Location: /");
                } catch (Exception $e) {
                    echo "exception";
                }
            } else {
                echo "fehler";
            }
        }
    }

    public function createDir(){

        $galleryRepository = new GalleryRepository();
        $a = array();

        $result = $galleryRepository->getGalleries();
        while ($entry = $result->fetch_object()){
            $a[] = $entry;
        }

        foreach ($a as $gallery){
            if(!file_exists('data/'.$gallery->id)) {
                if(mkdir('data/'.$gallery->id)) {
                    if(!mkdir('data/' . $gallery->id . '/thumbs'))  {
                        die("There was a problem. Please try again!");
                    }
                }
            } else {
            }
        }
    }

    public function notFound() {
        $view = new View('default_error');
        $view->title = "Die aufgerufene Seite wurde nicht gefunden.";
        $view->display();
    }
}

