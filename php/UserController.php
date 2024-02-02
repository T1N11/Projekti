<?php

    include "User.php";

    class UserController {
        private $dbconn;

        public function __construct($dbconn) {
            $this->dbconn = $dbconn;
        }

        public function getUsers() {
            $query = "SELECT * FROM users";
            $results = mysqli_query($this->dbconn, $query);
            
            $users = [];

            if ($results && mysqli_num_rows($results) > 0) {
                while ($row = $results->fetch_assoc()) {
                    $user = new User (
                        $row['userid'],
                        $row['username'],
                        $row['email'],
                        $row['password'],
                        $row['accountType']
                    );
                    $users[] = $user;
                }
            }
            return $users;
        }

        public function UsersOnly() {
            $users = $this->getUsers();
            $usersOnly = [];

            foreach ($users as $user) {
                if ($user->getAccountType() == 0) {
                    $usersOnly[] = $user;
                }
            }

            return $usersOnly;
        }


        public function emailExists($email) {
            $users = $this->getUsers();

            foreach ($users as $user) {
                if ($user->getEmail() == $email) {
                    return true;
                }
            }
            return false;
        }

        public function validateUserData($username, $email, $password) {
            $nameRegex = '/^[a-zA-Z0-9\s]+$/';
            $emailRegex = '/^[a-zA-Z._-]+@[a-z]+\.[a-z]{2,3}$/';
        
            $errorMessage = "";
        
            if ($this->emailExists($email)) {
                $errorMessage .= 'Email already exists!<br>';
            }
            
            return ($errorMessage === "") ? true : $errorMessage;
        }

        
        public function insertUser($username, $email, $password, $confirmPassword){
            $validation = $this->validateUserData($username, $email, $password);
            

            $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

            if ($validation === true) {
                if(mysqli_query($this->dbconn, $query)) {
                    return true;
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($this->dbconn);
                }
            } else { 
                return false;
            }

        }
        
        public function getUsername($email) {
            $users = $this->getUsers();

            foreach ($users as $user) {
                if ($user->getEmail() === $email) {
                    return $user->getUsername();
                }
            }
        }

        public function deleteUser($userid) {
            $users = $this->getUsers();

            foreach ($users as $user) {
                if ($user->getUserID() == $userid) {
                    $query = "DELETE FROM users WHERE userid = $userid";
                    $execute = mysqli_query($this->dbconn, $query);
                    
                    if ($execute) {
                        return true;
                    }
                }
            }

            return false;
        }

        public function is_Admin($email) {
            $users = $this->getUsers();

            foreach ($users as $user) {
                if ($user->getEmail() == $email && $user->getAccountType() == 1) {
                    return 'admin';
                }
            }

            return 'user';
        }

        public function authenticateUser($email, $password) {
            $users = $this->getUsers();
            
            foreach ($users as $user) {
                if ($user->getEmail() === $email && $user->getPassword() === $password) {
                    return true;
                }
            }

            return false;
        }
    }
?>