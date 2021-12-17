<?php 

class Post {

    public $id;
    public $title;
    public $content;
    public $published_time;    
    public $image;
    public $errors;

    public static function listPosts($conn){

        $sql = "SELECT * FROM post ORDER BY published_time;";

        $result = $conn -> query($sql);

        return $result->fetchAll(PDO::FETCH_ASSOC);

    }


    public static function getPage($conn, $limit, $offset){

        $sql = "SELECT a.*, category.name As category_name FROM 
        (SELECT * FROM post ORDER BY published_time LIMIT :limit OFFSET :offset) As a
        LEFT JOIN post_category ON a.id = post_category.post_id LEFT JOIN category ON post_category.category_id = category.id;
        ";

        $stmt = $conn -> prepare($sql);
        $stmt -> bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt -> bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt -> execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $posts = [];

        $previous_id = null;

        foreach ($results as $result) {
            $post_id = $result['id'];
            
            if($post_id != $previous_id){
                $result['category_names'] = [];
                $posts[$post_id] =$result;
            }

            $posts[$post_id]['category_names'][] = $result['category_name'];

            $previous_id = $post_id;

        }

        return $posts;
    }

    public static function getPostByID($conn, $id){

        $sql = "SELECT * FROM post WHERE id=:id";

        $stmt = $conn -> prepare($sql);
        $stmt -> blindValue(':id', $id, PDO::PARAM_INT);
        $stmt -> setFetchMode(PDO::FETCH_CLASS, 'Post');

        if($stmt->execute()) {
            return $stmt -> fetch();
        }
    }


    public static function getPostsWithCategories($conn, $id) {
        $sql = "SELECT post.*, category.name AS category_name FROM post LEFT JOIN post_category ON post.id = post_category.post_id 
        LEFT JOIN category ON  category.id = post_category.category_id WHERE post.id = :id;";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }


    public function listCategories($conn) {

        $sql = "SELECT category.* FROM category JOIN post_category ON category.id = post_category.category_id WHERE post_id=:id;";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }


}