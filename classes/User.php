<?php

class User {

    public $id;
    public $username;
    public $password;

    public static function authenticate($conn, $username, $password){

        $sql = "SELECT * FROM user WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS,'User');
        $stmt->execute();

        // if($user = $stmt->fetch()){
            // return password_verify($password, $user->password);
            // return true
        // }

        $user = $stmt -> fetch();
        
        // print_r($user);
        // var_dump($user);
        // print($password);
        if($user){
            if($user -> password === $password){
                return true;
            }
        }

    }

}