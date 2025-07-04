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
       
         if ($success) {
              
              exit;
         } else {
            $error='Aucun user supprimé.';
              http_response_code(404);    
         }
         header('Location: /mangatheque/');
         exit;
    }
    public function updateUser(int $id) {
     if($_SERVER['REQUEST_METHOD']== 'GET'){
         $modelUser = new ModelUser();
         $user = $modelUser->getOneUserById($id);

     }
 
        if($user=== null){
            $error="Aucun user trouvé";
            header('location:/managatheque/');
        
   
     exit;
        }  require './view/user/update-form.php';

     if($_SERVER['REQUEST_METHOD']== 'POST'){

     }

    
     http_response_code(404);
      header('location:/managatheque/');
       exit;
    }
}