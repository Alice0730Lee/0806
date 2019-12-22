<?php
require_once "bootstrap.php";

$user = $entityManager->find('User', $_POST["id"]);
$entityManager->remove($user);
$entityManager->flush();
