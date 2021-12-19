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

        $sql = "SELECT a.*, category.name As category_name 
        FROM (SELECT * 
        FROM post ORDER BY published_time 
        LIMIT :limit 
        OFFSET :offset) As a
        LEFT JOIN post_category 
        ON a.id = post_category.post_id 
        LEFT JOIN category 
        ON post_category.category_id = category.id;";


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
        $stmt -> bindValue(':id', $id, PDO::PARAM_INT);
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

    public function update($conn){

        if($this->validate()){

            $sql = "UPDATE post SET title = :title, content = :content, published_time = :published_time WHERE id=:id";

            $stmt = $conn -> prepare($sql);
            $stmt -> bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt -> bindValue(':content', $this->content, PDO::PARAM_STR);
            $stmt -> bindValue(':id', $this->id, PDO::PARAM_INT);

            if ($this->published_time == '') {
                $stmt->bindValue(':published_time', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':published_time', $this->published_time, PDO::PARAM_STR);
            }

            return $stmt->execute();

        } else {
            return false;
        }


    }

    public function setCategories($conn, $ids){

        if($ids){

            $sql = "INSERT IGNORE INTO post_category (post_id, category_id) VALUES";

            $values = [];

            foreach($ids as $id){
                $values[] = "({$this->id}, ?)";
            }

            $sql .= implode(", ", $values);

            $stmt = $conn->prepare($sql);

            foreach($ids as $i => $id){
                $stmt->bindValue($i+1 ,$id, PDO::PARAM_INT);
            }



            $stmt -> execute();

        }

        $sql = "DELETE FROM post_category WHERE post_id = {$this->id}";

        if($ids){

            $placeholders = array_fill(0, count($ids), '?');

            $sql .= " AND category_id NOT IN (" . implode(", ", $placeholders) . ")";

        }

        $stmt = $conn -> prepare($sql);

        foreach($ids as $i => $id){
            $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
        }

        $stmt->execute();


    }

    protected function validate(){

        if($this->title===''){
            $this->errors[] = 'Title is required';
        }

        if($this->content === ''){
            $this->errors[] = 'Content is required';
        }

        if($this->published_time !== ''){
            $date_time = date_create_from_format('Y-m-d H:i:s', $this->published_time);

            if($data_time === false){
                $this->errors[] = 'Invalid date and time';
            } else{
                $data_errors = date_get_last_errors();

                if($data_errors['warning_count']>0){
                    $this->errors[] = 'Invalid date and time';
                }
            }

        }

        return empty($this->errors);

    }


    public function delete($conn){


        $sql = "DELETE FROM post WHERE id = :id";
        $conn -> prepare($sql);

        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

        return $stmt->execute();

    }


    public function create($conn){

        if($this->validate){
            $sql = "INSERT INTO post (title, content, published_time) VALUES (:title, :content, :published_time) ;";

            $stmt = $conn->prepare($conn);
            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);
    
            if ($this->published_time == '') {
                $stmt->bindValue(':published_time', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':published_time', $this->published_time, PDO::PARAM_STR);
            }
            //
            if($stmt->execute()){
                $this->id = $conn -> lastInsertId();
                return true;
            }

        } else{
            return false;
        }

    }


    public static function getTotal($conn){
        //
        return $conn -> query("SELECT COUNT(*) FROM post") -> fetchColumn();

    }


    public function setImageFile($conn, $filename){

        $sql = "UPDATE post SET image_file = :image_file WHERE id = :id;";

        $stmt = $conn -> prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':image_file', $filename, $filename == null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        return $stmt->execute();
        
    }


}