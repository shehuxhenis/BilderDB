<?php

require_once '../lib/Repository.php';

class PictureRepository extends Repository {

    public function upload($gallery_id, $file_name, $title, $desc)
    {
        $query = "insert into picture (gallery_id, filename, name, description) values (?, ?, ?, ?);";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('isss', $gallery_id, $file_name, $title, $desc);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
            return false;
        } else {
            return true;
        }
    }

    public function deletePicture($id) {
        $query = "delete from picture where id = ?;";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $id);

        if (!$statement->execute()) {
            return false;
        } else {
            return true;
        }

    }

    public function getPictureNameById($id) {
        $query = "select filename from picture where id = ?;";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();

        $result = $statement->get_result();

        $row = $result->fetch_object();

        return $row;
    }

}
