<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>mirjalol.algol.uz</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font: 14px sans-serif;
        }
    </style>
</head>
<body>
<div class="page-header">
    <h1>Salom, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>, <b><?php echo htmlspecialchars($_SESSION["id"]); ?></b>. Saytingizga xush kelibsiz.</h1>
</div>
<p>
    <a href="reset-password.php" class="btn btn-success">Parolni o'zgartiramizmi?</a>
    <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
</p>
</body>
</html>