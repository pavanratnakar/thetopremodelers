<?php
class Login {
    static public function start ($logoff) {
        session_name("topremodelers");
        // Starting the session
        session_set_cookie_params(2*7*24*60*60);
        // Making the cookie live for 2 weeks
        session_start();
        if ($logoff) {
            Login::logoff($logoff);
        }
    }
    static public function checkIfLoggedIn () {
        if ($_SESSION["usr"] && $_SESSION["id"]) {
            return true;
        }
        return false;
    }
    static public function loginSuccess ($url) {
        if (!$url) {
            $url = "index.php";
        }
        header("Location: ".$url);
    }
    static public function logoff ($url) {
        $_SESSION = array();
        session_destroy();
        header("Location: " . $url);
        exit;
    }
}
?>