<?php

class Database
{


    public function connection()
    {
        $db_host = "localhost";
        $db_name = "myblog";
        $db_username = "admin";
        $db_password = "admin";
        $dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8';

        try {

            $db = new PDO($dsn, $db_username, $db_password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;

        } catch (PDOException $e) {

            echo $e->getMessage();
            exit;

        }
    }
}

$db = new Database();
return $db->connection();
