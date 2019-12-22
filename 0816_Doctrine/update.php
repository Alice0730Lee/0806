<?php
// update_user.php <id> <new-name>
require_once "bootstrap.php";

if (isset($_POST["btnUpdate"])) {
    $id = $_POST["upId"];
    $newContent = $_POST["upCon"];

    $user = $entityManager->find('User', $id);
    if ($user === null) {
        echo "user $id does not exist.\n";
        exit(1);
    }
    $user->setContent($newContent);
    $entityManager->flush();

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
