<?php

require "../utils/init.php";

if(!Auth::isLogin()){
    die("please login first!");
}

$conn = require "../classes/Database.php";

$post = Post::getPostByID($conn, $_GET['id']);
var_dump($post);


if($_SERVER["REQUEST_METHOD"] == "POST") {



    if($post->delete($conn)){
        var_dump('delete ok');
        Url::redirect("index.php");

    }

}

?>

<?php require "../components/header.php" ?>

<h2>Delete Post</h2>

<form method="post">

    <p>Are you sure?</p>

    <button>Delete</button>
    <a href="post.php?id=<?= $post->id; ?>">Cancel</a>

</form>

<?php require "../components/footer.php" ?>