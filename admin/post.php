<?php

require '../utils/init.php';

if(! Auth::isLogin()){
    die("Please login!");
}

$conn = require '../classes/Database.php';

$post = Post::getPostsWithCategories($conn, $_GET['id']);
//post records with different categories in an Array


?>

<?php require '../components/header.php' ;?>

    <article>
        <h2><?php echo $post[0]['title']; ?></h2>

        <p>Categories: <?php foreach($post as $item) : ?></p>
            <?php echo $item['category_name']; ?>

        <?php endforeach; ?>

        <p><?php echo $post[0]['content']; ?></p>
        
        <?php if ($post[0]['image']) : ?>
            <img src="/files/<?= $post[0]['image']; ?>">
        <?php endif; ?>

    </article>

    <a href="edit-post.php?id=<?php echo $post[0]['id']; ?>">Edit</a>
    <a href="delete-post.php?id=<?php echo $post[0]['id']; ?>">Delete</a>
    <a href="edit-post-image.php?id=<?php echo $post[0]['id'] ?>">Edit Image</a>

<?php require '../components/footer.php' ;?>