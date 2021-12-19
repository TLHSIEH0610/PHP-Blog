<?php

require 'utils/init.php';

$conn = require 'classes/Database.php';

$post = Post::getPostsWithCategories($conn, $_GET['id']);
//post records with different categories in an Array
var_dump($post);

?>

<?php require 'components/header.php' ;?>

    <article>
        <h2><?php echo $post[0]['title']; ?></h2>

        <p>Categories: <?php foreach($post as $item) : ?></p>
            <?php echo $item['category_name']; ?>

        <?php endforeach; ?>

        <p><?php echo $post[0]['content']; ?></p>
        
        <img src="" alt="">

    </article>



<?php require 'components/footer.php' ;?>