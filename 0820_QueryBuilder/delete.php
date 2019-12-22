<?php
require_once "bootstrap.php";

$query = $qb->delete('User', 'u')
            ->where('u.id = ?1')
            ->setParameter(1, $_POST["id"])
            ->getQuery();
$query->execute();

header("Location: alice0816.php");
