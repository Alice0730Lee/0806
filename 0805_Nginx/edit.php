<?php
    require_once("config.php");
    $link = mysqli_connect($dbhost, $dbuser,$dbpass) or die("Unable to connect to SQL server");
    mysqli_select_db($link, $dbname);

    if (isset($_POST['btnUpdate'])) {
        $sqlUpdate = "UPDATE user SET content='".$_POST['update']."' WHERE id='".$_POST['upId']."'";
        $resultUpdate = mysqli_query($link,$sqlUpdate);
        header("Location: alice0813.php");
    }
    if (isset($_POST['btnCancel'])) {
        header("Location: alice0813.php");
    }

    echo "<form method='post' action='edit.php'>";
    echo "<input type='text' name='update'/>";
    echo "<input type='text' name='upId' value=".$_POST['id']." style='display:none'/>";//存要修改的id
    echo "<input type='submit' name='btnUpdate' value='確定修改'/>";
    echo "<input type='submit' name='btnCancel' value='取消修改'/>";
    echo "<form>";
