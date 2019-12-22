<?php
require_once "bootstrap.php";

$newUserContent = $_POST['con'];
$newUserName = $_POST['name'];

$user = new User();
$user->setContent($newUserContent);
$user->setName($newUserName);
$entityManager->persist($user);//執行
$entityManager->flush();//更新資料庫

header("Location: alice0816.php");
