<?php
    if (isset($_POST["btnC"])) {
        require_once "create.php";
    }
    if (isset($_POST["btnUp"])) {
        require_once "update.php";
    }
    if (isset($_POST["btnD"])) {
        require_once "delete.php";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>留言板</title>
</head>
<body>
    <div>
        <?php
            require_once "show.php";
            
            foreach ($users as $user) {
                echo "<form method='post' action=''>";
                echo $user->getContent()."<br>";
                echo "by. ".$user->getName()."<br>";
                echo $user->getDate()->format("Y-m-d H:i:s")."<br>";
                echo "<input type='text' name='id' value='".$user->getId()."' style='display:none'/>";
                echo "<input type='submit' name='btnUp' value='修改留言'>";
                echo "<input type='submit' name='btnD' value='刪除留言'><hr>";
                echo "</form>";
            }
        ?>
    </div>
    <form method="post" action="">
        留言:<input type="text" name="con"/><br>
        ID:<input type="text" name="name"/>
        <input type="submit" name="btnC" value="送出">
    </form>
</body>
</html>