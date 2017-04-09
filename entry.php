<?php
/**
 * Created by PhpStorm.
 * User: kandorm
 * Date: 17-4-9
 * Time: 上午10:31
 */
    require_once 'common.php';

    if($_GET['type'] == 'logout') {
        $user = null;
        setcookie("user_id", null);
        header("Location:".$HOME_PAGE);
    }

?>