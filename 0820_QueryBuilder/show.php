<?php
require_once "bootstrap.php";

$query = $qb->select('u')
            ->from('User', 'u')
            ->getQuery();
$users = $query->execute();
