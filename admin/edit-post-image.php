<?php

require "../utils/init.php";

if(!Auth::isLogin()){
    die("please login first!");
}

$conn = require "../classes/Database.php";

$post = Post::getPostByID($conn, $_GET['id']);


 
if($_SERVER["REQUEST_METHOD"] == "POST") {

    try{

        if (empty($_FILES)) {
            throw new Exception('Invalid upload');
        }

        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_OK:
                break;

            case UPLOAD_ERR_NO_FILE:
                throw new Exception('No file uploaded');
                break;

            case UPLOAD_ERR_INI_SIZE:
                throw new Exception('File is too large (from the server settings)');
                break;

            default:
                throw new Exception('An error occurred');
        }

        // Restrict the file size
        if ($_FILES['file']['size'] > 1000000) {

            throw new Exception('File is too large');

        }

        $mime_types = ['image/gif', 'image/png', 'image/jpeg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES['file']['tmp_name']);

        if ( ! in_array($mime_type, $mime_types)) {

            throw new Exception('Invalid file type');

        }

        
        // Move the uploaded file
        $pathinfo = pathinfo($_FILES["file"]["name"]);

        $base = $pathinfo['filename'];

        // Replace any characters that aren't letters, numbers, underscores or hyphens with an underscore
        $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $base);

        // Restrict the filename to 200 characters
        $base = mb_substr($base, 0, 200);

        $filename = $base . "." . $pathinfo['extension'];

        $destination = "../files/$filename";

        // Add a numeric suffix to the filename to avoid overwriting existing files
        $i = 1;

        while (file_exists($destination)) {

            $filename = $base . "-$i." . $pathinfo['extension'];
            $destination = "../files/$filename";

            $i++;
        }

        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {

            $previous_image = $post->image;

            if ($post->setImageFile($conn, $filename)) {

                if ($previous_image) {
                    unlink("../files/$previous_image");
                }

                Url::redirect("/admin/edit-post-image.php?id={$post->id}");
            }
        }else {

            throw new Exception('Unable to move uploaded file');

        }

    }catch(Exception $e){
        $error = $e -> getMessage();
    }

}

?>

<?php require "../components/header.php" ?>

<h2>Edit Image</h2>

<?php if($post->image) :?>
    <img src="../files/<?php echo $post->image ?>">
    <a href="delete-post-image.php?id=<?php echo $post->id?>">Delete</a>
<?php endif; ?>

<?php if(isset($error)): ?>
    <p><?php echo $error ?></p>
<?php endif; ?> 

<form method="post" enctype="multipart/form-data">

    <div>
        <label for="file">Image</label>
        <input type="file" name="file" id="file">
    </div>

    <button>Upload</button>

</form>

<?php require "form.php" ?>

<?php require "../components/footer.php" ?>