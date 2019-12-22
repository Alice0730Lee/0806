<?php
require_once "bootstrap.php";

$user = $qb->insert('User')
            ->values(
                array(
                    'content' => '?1',
                    'name' => '?2'
                )
            )
            ->setParameter(1, $_POST['con'])
            ->setParameter(2, $_POST['name'])
            ->getQuery();
// $user->execute();

// $query = $qb->insert('User')
//             ->setValue('content','?1')
//             ->setValue('name','?2')
//             ->setParameter(1, $newUserContent)
//             ->setParameter(2, $newUserName)
//             ->getQuery();
// $user->execute();