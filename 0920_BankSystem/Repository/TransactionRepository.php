<?php

namespace AppBundle\Repository;

class TransactionRepository extends \Doctrine\ORM\EntityRepository
{
    public function page($transactionQuery, $isPage)
    {
        $transaction = $transactionQuery->getQuery()->getResult();

        $count = count($transaction);
        $per = 10;
        $transactions["pages"] = ceil($count/$per);

        if (!isset($isPage)) {
            $page = 1;
        } else {
            $page = intval($isPage);
        }

        $start = ($page-1)*$per;

        $result = $transactionQuery
            ->setFirstResult($start)
            ->setMaxResults($per)
            ->getQuery();
        $transactions["result"] = $result->getResult();

        return $transactions;
    }
}
