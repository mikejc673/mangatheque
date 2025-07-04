<?php
class ModelUser extends Model {
    public function getUsers() : array {
        // Ensure PDO is referenced with a leading backslash in case of namespace issues
        $db = new \PDO('mysql:host=localhost;dbname=mangatheque', 'root','root');
        $query = $db->prepare('SELECT id, pseudo, email, password FROM user');
        $query->execute();

        $arrayUser = [];
        while($user = $query->fetch(\PDO::FETCH_ASSOC)){
            $arrayUser[] = new User($user['id'], $user['pseudo'], $user['email'], $user['password']);
        }

        return $arrayUser; 
    }

    public function getOneUserById(int $id) : ?User {
        $db = new \PDO('mysql:host=localhost;dbname=mangatheque', 'root');

        $req = $db->prepare('SELECT id, pseudo, email, password FROM user WHERE id = :id ');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        $req->execute();

        $user = $req->fetch(\PDO::FETCH_ASSOC);

        return $user ? new User($user['id'], $user['pseudo'], $user['email'], $user['password']) : null;
    }

    public function deleteUserById(int $id) : bool {
        $db = new \PDO('mysql:host=localhost;dbname=mangatheque', 'root');
        $req = $db->prepare('DELETE FROM user WHERE id = :id');
        $req->bindParam(':id', $id, \PDO::PARAM_INT);
        return $req->execute();
    }
}