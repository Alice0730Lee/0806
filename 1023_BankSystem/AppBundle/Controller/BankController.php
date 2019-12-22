<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bank;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Acl\Exception\Exception;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\DBAL\LockMode;

class BankController extends Controller
{
    /**
     * 選擇存提款或查詢
     *
     * @Route("/choose", name="choose", methods={"PUT"})
     */
    public function chooseAction(Request $request)
    {
        $id = $request->get('id');
        $isIn = $request->get('isIn');
        $isOut = $request->get('isOut');
        $isSearch = $request->get('isSearch');

        if (isset($isIn)) {
            return $this->render('io.html.twig', [
                'btn' => 'isIn',
                'value' => '存款',
                'id' => $id
            ]);
        }

        if (isset($isOut)) {
            return $this->render('io.html.twig', [
                'btn' => 'isOut',
                'value' => '提款',
                'id' => $id
            ]);
        }

        if (isset($isSearch)) {
            $id = $request->get('id');
            $page = $request->get('page');
            $date = $request->get('searchDate');

            $em = $this->getDoctrine()->getManager();
            $user = $em->find('AppBundle\Entity\User', $id);

            $bankQuery = $em->createQueryBuilder()
                ->select('t')
                ->from('AppBundle\Entity\Bank', 't')
                ->where('t.user = :user')
                ->andwhere('t.date like :date')
                ->setParameter('user', $user)
                ->setParameter('date', $date.'%');
            $bank = $em->getRepository(Bank::class)->page($bankQuery, $page);

            return $this->render('search.html.twig', [
                'id' => $id,
                'bank' => $bank['result'],
                'date' => $date,
                'pages' => $bank['pages'],
                'page' => $page,
            ]);
        }
    }

    /**
     * 存入或提出款項
     *
     * @Route("/doCash", name="doCash", methods={"POST"})
     */
    public function doCashAction(Request $request)
    {
        if ($request->isXmlHttpRequest() || $request->get('id')) {
            $redis = $this->get('snc_redis.default');
            $em = $this->getDoctrine()->getManager();

            $isIn = $request->get('isIn');
            $isOut = $request->get('isOut');
            $cash = $request->get('cash');
            $id = $request->get('id');
            $date = new \DateTime('now');

            $user = $em->find('AppBundle\Entity\User', $id);
            $redis->hsetnx($id, 'account', $user->getAccount());
            $redis->hsetnx($id, 'total', (int)$user->getTotal());
            $redis->hsetnx($id, 'version', 1);

            $version = $redis->hget($id, 'version');
            $total = $redis->hget($id, 'total');

            if ($cash == 0 || !preg_match('/^\d+\.?\d{0,4}$/', $cash)) {
                return false;
            }

            $redis->multi();

            if (isset($isIn)) {
                $redis->hincrby($id, 'version', 1);
            }

            if (isset($isOut)) {
                if ($total - $cash < 0) {
                    throw new Exception('cash is not enough');
                } else {
                    $cash = -$cash;
                    $redis->hincrby($id, 'version', 1);
                }
            }

            $result = $redis->exec();

            if ($redis->hget($id, 'version') == $version + 1) {
                $redis->hincrby($id, 'total', $cash);
                $jsonData = [
                    'user_id' => $id,
                    'user_account' => $user->getAccount(),
                    'cash' => $cash,
                    'total' => $redis->hget($id, 'total'),
                    'date' => $date->format('Y-m-d H:i:s')
                ];

                $redis->lpush('bank', json_encode($jsonData));

                return new JsonResponse($jsonData);
            } else {
                return new Exception('version is wrong');
            }
        }
    }

    /**
     * 回傳交易紀錄
     *
     * @Route("/search", name="search", methods={"GET"})
     */
    public function searchAction(Request $request)
    {
        if ($request->isXmlHttpRequest() || $request->get('id')) {
            $id = $request->get('id');
            $page = $request->get('page');
            $date = $request->get('searchDate');

            $em = $this->getDoctrine()->getManager();
            $user = $em->find('AppBundle\Entity\User', $id);

            $bankQuery = $em->createQueryBuilder()
                ->select('t')
                ->from('AppBundle\Entity\Bank', 't')
                ->where('t.user = :user')
                ->andwhere('t.date like :date')
                ->setParameter('user', $user)
                ->setParameter('date', $date.'%');
            $banks = $em->getRepository(Bank::class)->page($bankQuery, $page);

            foreach ($banks['result'] as $bank) {
                $temp = [
                    'cash' => $bank->getCash(),
                    'total' => $bank->getTotal(),
                    'date' => $bank->getDate()->format('Y-m-d H:i:s')
                ];

                $jsonData[] = $temp;
            }

            return new JsonResponse($jsonData);
        } else {
            return $this->render('search.html.twig');
        }
    }
}
