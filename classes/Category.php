<?php 

class Category {

    public static function listCategories($conn){

        $sql = "SELECT * FROM category ORDER BY name;";

        $results = $conn -> query($sql);

        return $results -> fetchAll(PDO::FETCH_ASSOC);

    }

}