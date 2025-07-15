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
    }

    public function DeleteUserById (int $id){
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
    public function UpdateUser(int $id){
     if($_SERVER['REQUEST_METHOD']== 'GET'){
         $modelUser = new ModelUser();
         $user = $modelUser->getOneUserById($id);

     }
 
        if($user=== null){
            $error="Aucun user trouvé";
            header('location:/mangatheque/');
        
   
     exit;
        }  
        require './view/user/update-form.php';
        exit;

        public function UserLogin(int $id);
        $ModelUser=$User();

     if($_SERVER['REQUEST_METHOD']== 'POST'){
            $modelUser = new ModelUser();
            $pseudo = trim($_POST['pseudo']);
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);
            $req = $modelUser->updateOneUserById($id, $pseudo, $email, $password);

            if($req){
                $message = "User modifié avec succès";
                header('location:/mangatheque/user/'.$user->getId());
                exit;
            } 
            if($user === null){
                $error = "Aucun user trouvé";
     }

    
     http_response_code(404);
      header('location:/mangatheque/');
       exit;
    }
}