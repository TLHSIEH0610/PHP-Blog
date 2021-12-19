<?php 

require '../utils/init.php';

if(!Auth::isLogin()){
    die("please login first!");
}

$conn = require '../classes/Database.php';

$paginator = new Paginator($_GET['page']?? 1, 6, Post::getTotal($conn));

$posts = Post::getPage($conn, $paginator->limit, $paginator->offset);

?>

<?php require "../components/header.php" ?>

<h2>Administration</h2>

<p><a href="add-post.php">New Post</a></p>

<table>
    <thead>
        <tr>
            <th>Title</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($posts as $post) :?>
            <tr>
                <td>
                    <a href="post.php?id=<?php echo $post['id']; ?>"><?php echo $post['title'] ?></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>



<?php require "../components/footer.php" ?>