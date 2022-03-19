<?php

session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}

require_once "config.php";

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {

        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($connect, $sql)) {

            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {

                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {

                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            header("location: index.php");
                        } else {
                            $password_err = "Parol xato kiritildi!";
                        }
                    }
                } else {

                    $username_err = "Bu foydalanuvchi nomi bilan hisob topilmadi!";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($connect);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hisobga kirish</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body {
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font: 14px sans-serif;
        }

        .wrapper {
            width: 400px;
            padding: 20px;
            /*border: 0.5px solid #7d7d7d;*/
            box-shadow: 0px 0px 20px 1px #adadad;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h2 class="text-center">Hisobga kirish</h2>
    <p class="text-center">Hisobga kirish uchun maydonlarni to'ldiring.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label>Login</label>
            <input type="text" name="username" class="form-control" placeholder="Foydalanuvchi nomi" value="<?php echo $username; ?>">
            <span class="help-block"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Parol</label>
            <input type="password" name="password" class="form-control" placeholder="Parol">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">

        <?php
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        ?>

            <input type="submit" class="btn btn-primary" value="Hisobga kirish">
        </div>
        <p>Hali ro'yxatdan o'tmadingizmi? <a href="register.php">Hisob yaratish</a>.</p>
    </form>
</div>
</body>
</html>