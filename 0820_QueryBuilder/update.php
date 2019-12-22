<?php
require_once "bootstrap.php";

if (isset($_POST["btnUpdate"])) {
    $id = $_POST["upId"];
    $newContent = $_POST["upCon"];

    $query = $qb->update('User', 'u')
                ->set('u.content', '?1')
                ->where('u.id = ?2')
                ->setParameter(1, $newContent)
                ->setParameter(2, $id)
                ->getQuery();
    $query->execute();
    
    header("Location: alice0816.php");
}

if (isset($_POST["btnCancel"])) {
    header("Location: alice0816.php");
}

echo "<form method='post' action='update.php'>";
echo "<input type='text' name='upCon'/>";
echo "<input type='text' name='upId' value=".$_POST['id']." style='display:none'/>";//存要修改的id
echo "<input type='submit' name='btnUpdate' value='確定修改'/>";
echo "<input type='submit' name='btnCancel' value='取消修改'/>";
echo "<form>";
