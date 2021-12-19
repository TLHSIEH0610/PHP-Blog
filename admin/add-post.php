<?php

require "../utils/init.php";

if(!Auth::isLogin()){
    die("please login first!");
}

$post = new Post();

$category_ids = [];

$conn = require "../classes/Database.php";

$categories = Category::listCategories($conn);

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $post -> title = $_POST['title'];
    $post -> content = $_POST['content'];
    $post -> published_time = $_POST['published_time'];

    if($post->create($conn)){

        $post -> setCategories($conn, $category_ids);

        Url::redirect("/admin/post.php?id={$post -> id}");

    }

}

?>

<?php require "../components/header.php" ?>

<h2>New Post</h2>

<?php require "form.php" ?>

<?php require "../components/footer.php" ?>