<?php
require 'utils/init.php';
require 'components/header.php';

$conn = require 'classes/Database.php';

$paginator = new Paginator($_GET['page']?? 1, 5, Post::getTotal($conn));

// var_dump($paginator);

$posts = Post::getPage($conn, $paginator->limit, $paginator->offset);

// var_dump($posts );

?>


<ul>
    <?php foreach($posts as $post) :?>
        <li>
            <h2><a href="post.php?id=<?php echo $post['id']?>"><?php echo $post['title'] ;?></a></h2>
            <p>Categories: <?php $post['category_name'] ?></p>
            <p><?php echo $post['content'] ?></p>
        </li>
    <?php endforeach ;?>
</ul>




<?php

require 'components/footer.php';

?>