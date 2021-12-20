<?php

require "../utils/init.php";

if(!Auth::isLogin()){
    die("please login first!");
}

$conn = require "../classes/Database.php";

$post = Post::getPostByID($conn, $_GET['id']);


//array_column() returns the values from a single column of the array
$category_ids = array_column($post -> listCategories($conn), 'id');

$categories = Category::listCategories($conn);


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $post -> title = $_POST['title'];
    $post -> content = $_POST['content'];
    $post -> published_time = $_POST['published_time'];
    
    $category_ids = $_POST['category'] ?? [];

    if($post->update($conn)){

        $post -> setCategories($conn, $category_ids);

        Url::redirect("/admin/post.php?id={$post -> id}");

    }

}

?>

<?php require "../components/header.php" ?>

<h2>Edit Post</h2>

<?php require "form.php" ?>

<?php require "../components/footer.php" ?>