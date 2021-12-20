<?php

require "../utils/init.php";

if(!Auth::isLogin()){
    die("please login first!");
}

$conn = require "../classes/Database.php";

$post = Post::getPostByID($conn, $_GET['id']);



if($_SERVER["REQUEST_METHOD"] == "POST") {



    $previous_image = $post->image;

    if ($post->setImageFile($conn, null)) {

        if ($previous_image) {
            unlink("../uploads/$previous_image");
        }

        Url::redirect("/admin/edit-post-image.php?id={$post->id}");

    }

}

?>

<?php require "../components/header.php" ?>

<h2>Delete post image</h2>

<form method="post">

    <p>Are you sure?</p>

    <button>Delete</button>
    <a href="edit-post-image.php?id=<?= $post->id; ?>">Cancel</a>

</form>

<?php require "../components/footer.php" ?>