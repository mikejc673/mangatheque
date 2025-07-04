<?php
class ControllerUser {
    public function OneUserById(int $id){
        $modelUser = new ModelUser();
        $user = $modelUser->oneUserById($id);
       
        if ($user == null) {
            http_response_code(404);
            require './view/404.php';
            exit;
        } 
        require './view/user/one-User.php';
    }

    public function deleteUserById(int $id) {
       $modelUser = new ModelUser();
       $success = $modelUser->deleteUserById($id);
       var_dump($success);
    }
}

?>
