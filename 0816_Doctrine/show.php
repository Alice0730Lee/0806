<?php
require_once "bootstrap.php";

$userRepository = $entityManager->getRepository('User');
$users = $userRepository->findAll();
//兩種是一樣的
$query = $entityManager->createQuery('SELECT u FROM User u');
$users = $query->getResult();