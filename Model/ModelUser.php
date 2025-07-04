<?php
class ModelUser extends Model {
    public function getUsers() : array {
        $query = $this->getDb()->query('SELECT id, pseudo, email, password FROM user');
        $arrayUser = [];

        while($user = $query->fetch(PDO::FETCH_ASSOC)){
            $arrayUser[] = new User($user);
        }

        return $arrayUser; 
    }

    public function getOneUserById(int $id) : ?User {
        $req = $this->getDb()->prepare('SELECT id, pseudo, email, password, created_at FROM user WHERE id = :id ');
        $req->bindParam(':id', $id, PDO::PARAM_INT);

        $req->execute();

        $user = $req->fetch(PDO::FETCH_ASSOC);

        return $user ? new User($user) : null;
    }

    public function deleteOneUserById(int $id) : bool {
        $req = $this->getDb()->prepare('DELETE FROM user WHERE id = :id');
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();

        return $req->rowCount() > 0;
    }
}