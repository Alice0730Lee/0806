<?php
    require_once("config.php");
    $link = mysqli_connect($dbhost, $dbuser,$dbpass) or die("Unable to connect to SQL server");
    mysqli_select_db($link, $dbname);
    $sqlCommand = "select * from user";//列出資料表內的所有資料
    $result = mysqli_query($link,$sqlCommand);
    $content = [];//用陣列存取
    $row = mysqli_fetch_assoc($result);
    //直到$row沒有值
    while (isset($row) != "") {
        $content[] = $row;
        $row = mysqli_fetch_assoc($result);
    }

    if (isset($_POST["btnS"])) {
        if ($_POST['content'] != "" and $_POST['userName'] != "") {
            $sqlInsert = "INSERT INTO user (content,userName) values ('".$_POST['content']."','".$_POST['userName']."')";
            $resultPost = mysqli_query($link,$sqlInsert);
            header("Location: alice0813.php");
        } else {
            echo "<script>alert('欄位不得為空')</script>";
        }
    }
    if (isset($_POST["btnD"])) {
        $sqlDelete = "DELETE FROM user WHERE id='".$_POST['id']."' ";
        $resultDelete = mysqli_query($link,$sqlDelete);
        header("Location: alice0813.php");
    }

    if (isset($_POST["btnUp"])) {
        include_once("edit.php");
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
            foreach ($content as $key => $value) {
                echo "<form method='post' action=''>";
                echo $value['id']."樓<br>";
                echo $value['content']."<br>";
                echo "by. ".$value['userName']." ";
                echo "Date: ".$value['date']."<br>";
                echo "<input type='text' name='id' value=".$value['id']." style='display:none'/>";
                echo "<input type='submit' name='btnUp' value='修改留言'/>";
                echo "<input type='submit' name='btnD' value='刪除留言'/><hr>";
                echo "</form>";
            }
        ?>
    
    </div>
    <form method="post" action="">
        留言:<input id="content" type="text" name="content"/><br>
        ID:<input type="text" name="userName"/>
        <input type="submit" name="btnS" value="送出">
    </form>
</body>
</html>
