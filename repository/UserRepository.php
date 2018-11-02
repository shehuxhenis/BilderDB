<?php

require_once '../lib/Repository.php';

/**
 * Das UserRepository ist zuständig für alle Zugriffe auf die Tabelle "user".
 *
 * Die Ausführliche Dokumentation zu Repositories findest du in der Repository Klasse.
 */
class UserRepository extends Repository
{
    /**
     * Diese Variable wird von der Klasse Repository verwendet, um generische
     * Funktionen zur Verfügung zu stellen.
     */
    protected $tableName = 'user';

    /**
     * Erstellt einen neuen benutzer mit den gegebenen Werten.
     *
     * Das Passwort wird vor dem ausführen des Queries noch mit dem SHA1
     *  Algorythmus gehashed.
     *
     * @param $firstName Wert für die Spalte firstName
     * @param $lastName Wert für die Spalte lastName
     * @param $email Wert für die Spalte email
     * @param $password Wert für die Spalte password
     *
     * @throws Exception falls das Ausführen des Statements fehlschlägt
     */
    public function create($email, $username, $password)
    {
        $query = "select * from user where email = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('s', $email);
        $statement->execute();

        $result = $statement->get_result();
        if ($result->num_rows == 0) {

            $password = password_hash($password, PASSWORD_DEFAULT);

            $query = "Insert into user (email, username, password) values (?,?,?);";

            $statement = ConnectionHandler::getConnection()->prepare($query);
            $statement->bind_param('sss', $email, $username, $password);

            if (!$statement->execute()) {
                throw new Exception($statement->error);
            }
        }

    }

    public function login($email, $password) {
        $query = "select * from user where email = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('s', $email);
        $statement->execute();

        $result = $statement->get_result();
        $user = $result->fetch_object();

        if (password_verify($password, $user->password)) {
            $_SESSION['user'] = $user;
            return true;
        } else {
            return false;
        }
    }

    public function deleteUser($id) {
        $query = "delete from user where id = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('s', $id);
        if ($statement->execute()) {
            return true;
        } else {
            echo $statement->error;
            return false;
        }
    }

    public function alterUserName($username, $email) {
        $query = "update user set username = ? where email = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ss', $username, $email);
        $statement->execute();
    }

    public function alterUserPassword($email) {
        $query = "update user set password = ? where email = ?";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ss', $email);
        if($statement->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getUsers() {
        $query = "select * from user where admin = 0";
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->execute();
        return $statement->get_result();
    }
}
