<?php

namespace AppBundle\Repository;

class BankRepository extends \Doctrine\ORM\EntityRepository
{
    public function page($bankQuery, $isPage)
    {
        $bank = $bankQuery->getQuery()->getResult();

        $count = count($bank);
        $per = 10;
        $banks["pages"] = ceil($count/$per);

        if (!isset($isPage)) {
            $page = 1;
        } else {
            $page = intval($isPage);
        }

        $start = ($page - 1)*$per;

        $result = $bankQuery
            ->setFirstResult($start)
            ->setMaxResults($per)
            ->getQuery();
        $banks["result"] = $result->getResult();

        return $banks;
    }
}
