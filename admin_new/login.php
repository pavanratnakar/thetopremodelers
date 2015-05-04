<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
include_once(Config::$site_path.'/min/utils.php');
include_once(Config::$site_path.'/global/Class/login.class.php');
$link = mysql_connect(Config::$db_server, Config::$db_username, Config::$db_password) or die('Unable to establish a DB connection');
mysql_select_db(Config::$db_database, $link);
Login::start($_GET["logoff"]);
if (Login::checkIfLoggedIn()) {
    header('Location: http://www.topremodelers.com/admin_new/index.php');
}
if ($_POST["submit"] == "Login") {
    // Checking whether the Login form has been submitted
    $err = array();
    // Will hold our errors
    if (!$_POST["username"] || !$_POST["password"]) {
        $err[] = "All the fields must be filled in!";
    }
    if (!count($err)) {
        $_POST["username"] = mysql_real_escape_string($_POST["username"]);
        $_POST["password"] = mysql_real_escape_string($_POST["password"]);
        $row = mysql_fetch_assoc(mysql_query("SELECT id, email, usr FROM rene_members WHERE email='".$_POST["username"]."' AND pass='".md5($_POST["password"])."'"));
        if ($row["usr"]) {
            // If everything is OK login
            $_SESSION["usr"]=$row["usr"];
            $_SESSION["id"] = $row["id"];
        } else {
            $err[] = "Wrong username and/or password!";
        }
    }
    if ($err) {
        $_SESSION["msg"]["login-err"] = implode("<br />",$err);
    } else {
        Login::loginSuccess($_GET["return"] ? $_GET["return"] : "index.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <title>Topremodelers admin login</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <link type="text/css" rel="stylesheet" media="screen" href="<?php echo Minify_getUri('herve_admin_login_css') ?>"/>
</head>
<body>
    <?php
    if (!$_SESSION['id']) {
    ?>
    <div class="container">
        <form action="" method="post" class="form-signin">
            <?php
                if ($_SESSION['msg']['login-err']) {
                    echo '<div class="alert alert-danger" role="alert">'.$_SESSION['msg']['login-err'].'</div>';
                    unset($_SESSION['msg']['login-err']);
                }
            ?>
            <h2 class="form-signin-heading">Sign In</h2>
            <label for="username" class="sr-only" >Email address</label>
            <input type="email" id="username" name="username" placeholder="Enter email" class="form-control">
            <label for="password" class="sr-only">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" class="form-control">
            <button type="submit" name="submit" class="btn btn-lg btn-primary btn-block" value="Login">Submit</button>
        </form>
    </div>
    <?php } ?>
</body>
</html>