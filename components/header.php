<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diary</title>
</head>

<body>

    <h1>Welcome</h1>

    <ul>
        <li><a href="/">Home</a> </li>
        <li><a href="/album/">Album</a></li>
        <?php if (!Auth::isLogin()) : ?>
            <li><a href="/login.php">Login</a></li>
        <?php else : ?>
            <li><a href="/admin/">Admin</a></li>
            <li><a href="components/logout.php">Logout</a></li>
        <?php endif; ?>
    </ul>