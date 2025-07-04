<?php
class ControllerUser {
    public function OneUserById(int $id){
        $modelUser = new ModelUser();
        $user = $modelUser->getOneUserById($id);

        if ($user == null) {
            http_response_code(404);
            require './view/404.php';
            exit;
        } 
        require './view/user/one-User.php';
    }

    public function deleteUserById(int $id) {
       $modelUser = new ModelUser();
       $success = $modelUser->deleteOneUserById($id);
       
    }
}

?>
