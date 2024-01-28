<?php
class User {
    private $userid;
    private $username;
    private $email;
    private $password;
    private $accountType;

    public function __construct($userid, $username, $email, $password, $accountType) {
        $this->userid = $userid;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->accountType = $accountType;
    }

    public function getUserID() {
        return $this->userid;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAccountType() {
        return $this->accountType;
    }

    public function setUserID($userid) {
        $this->userid = $userid;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setAccountType($accountType) {
        $this->accountType = $accountType;
    }

    public function getPassword() {
        return $this->password;
    }
}

?>