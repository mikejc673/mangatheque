<?php
// entity/user.php encapsulation example
    class User {
        private int $id;
        private string $pseudo;
        private string $password;
        private string $email;
        private DateTimeImmutable $createdAt;

        public function __construct(int $id, string $pseudo, string $password, string $email) {
            $this->id = $id;
            $this->pseudo = $pseudo;
            $this->password = $password;
            $this->email = $email;
            $this->createdAt = new DateTimeImmutable();
        }

        public function getId(){
            return $this->id;//$this représente l'instance de la classe User
        }
        public function setId(int $id) :void{
            $this->id = $id;
        }
        public function getPseudo(): string {
            return $this->pseudo;
        }
        public function setPseudo(string $pseudo) :void {
            $this->pseudo = $pseudo;
        }
        public function getPassword(): string {
            return $this->password;
        }

        public function setPassword(string $password) :void{
            $this->password = $password;
        }

        public function getEmail(): string {
            return $this->email;
        }

    public function setEmail(string $email): void {
        $this->email = $email;
    }
        public function setCreatedAt(DateTimeImmutable $createdAt) {
            $this->createdAt = $createdAt;
        }

        public function getCreatedAt(): DateTimeImmutable {
            return $this->createdAt;
        }
    }
    $user = new User(1, 'Alice', 'password123', 'alice@example.com');
    $user2 = new User(2, 'Bob', 'securepass', 'bob@example.com');

    echo $user->getId().'<br>';
    echo $user2->getId().'<br>';

    $user2->setId(25);
    echo $user->getId().'<br>';
    echo $user2->getId().'<br>';

   