<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";


$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Yangi parolni kiriting.";
    } elseif (strlen(trim($_POST["new_password"])) < 8) {
        $new_password_err = "Parol 8 belgidan kam bo'lmasligi kerak.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Parolni tasdiqlang.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Parollar bir xil emas.";
        }
    }

    if (empty($new_password_err) && empty($confirm_password_err)) {
        $sql = "UPDATE users SET password = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($connect, $sql)) {

            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            if (mysqli_stmt_execute($stmt)) {

                session_destroy();
                header("location: login.php");
                exit();
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
    <title>Parolni o'zgartirish</title>
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
    <h2>Parolni yangilash</h2>
    <p>Parolni yangilash uchun maydonlarni to'ldiring.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
            <label>Yangi parol</label>
            <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
            <span class="help-block"><?php echo $new_password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label>Parolni tasdiqlang</label>
            <input type="password" name="confirm_password" class="form-control">
            <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-success" value="Tasdiqlash">
            <a class="btn btn-link" href="index.php">Orqaga qaytish</a>
        </div>
    </form>
</div>
</body>
</html>