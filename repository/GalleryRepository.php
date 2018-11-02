<?php

require_once '../lib/Repository.php';

class GalleryRepository extends Repository {

    public function create($user_id, $name, $desc, $public)
    {
        $query = "insert into gallery (user_id, name, description, public) values (?, ?, ?, ?)";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('issi', $user_id, $name, $desc, $public);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
            return false;
        } else {
            return true;
        }
    }

    public function getGalleries() {
        $query = "select id from gallery";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->execute();
        return $statement->get_result();
    }

    public function  getPictures($gallery_id) {
        $query = "select * from picture where gallery_id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $gallery_id);
        $statement->execute();

        $result = $statement->get_result();

        if(!$result) {
            throw new Exception($statement->error);
        }

        $rows = array();
        while ($row = $result->fetch_object()) {
            $rows[] = $row;
        }

        $result->close();

        return $rows;
    }

    public function getAllGalleries() {
        $query = "select * from gallery";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->execute();
        return $statement->get_result();
    }

    public function getGalleriesByUserId($uId) {
        $query = "select id from gallery where user_id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $uId);
        $statement->execute();
        return $statement->get_result();
    }

    public function deleteGalleryById($galleryId) {
        $query = "delete from gallery where id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $galleryId);
        $statement->execute();
    }

    public function getUserIdByGalleryId($galleryId) {
        $query = "select user_id from gallery where id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $galleryId);
        $statement->execute();
        return $statement->get_result();
    }

    public function getPublicGalleries() {
        $query = "select * from gallery where public = 1";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->execute();
        return $statement->get_result();
    }

}
