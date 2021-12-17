<?php

require 'utils/init.php';


if($_SERVER["REQUEST_METHOD"] === "POST") {

    $conn = require 'classes/Database.php';

    if(User::authenticate($conn, $_POST['username'], $_POST['password'])){
        Auth::login();
        Url::redirect('/');
    }else{
        $error = "login failed";
    }



}




?>

<?php require 'components/header.php' ?>

<h2>Login</h2>

<?php if(!empty($error)) :?>

    <p><?= $error ?></p>
    
<?php endif;?>

<form method='post'>

    <div>
        <label for="username">User</label>
        <input id="username" type="text" name="username">

    </div>
    <div>

        <label for="password">Password</label>
        <input id="password" type="password" name="password">
    </div>

    <button>Submit</button>

</form>


<?php require 'components/footer.php' ?>