<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Transaction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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

            $transactionQuery = $em->createQueryBuilder()
                ->select('t')
                ->from('AppBundle\Entity\Transaction', 't')
                ->where('t.user = :user')
                ->andwhere('t.date like :date')
                ->setParameter('user', $user)
                ->setParameter('date', $date.'%');
            $transaction = $em->getRepository(Transaction::class)->page($transactionQuery, $page);

            $qb = $em->createQueryBuilder()
                ->select('sum(t.cashIn - t.cashOut) as result')
                ->from('AppBundle\Entity\Transaction', 't')
                ->where('t.user = :user')
                ->setParameter('user', $user)
                ->getQuery();
            $sum = $qb->getResult();

            return $this->render('search.html.twig', [
                'id' => $id,
                'transaction' => $transaction['result'],
                'result' => $sum[0]['result'],
                'date' => $date,
                'pages' => $transaction['pages'],
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
            $isIn = $request->get('isIn');
            $isOut = $request->get('isOut');
            $cash = $request->get('cash');
            $id = $request->get('id');

            $em = $this->getDoctrine()->getManager();
            $user = $em->find('AppBundle\Entity\User', $id);

            $qb = $em->createQueryBuilder()
                ->select('sum(t.cashIn - t.cashOut) as result')
                ->from('AppBundle\Entity\Transaction', 't')
                ->where('t.user = :user')
                ->setParameter('user', $user)
                ->getQuery();
            $sum = $qb->getResult();

            if (!preg_match('/^\d+\.?\d{0,4}$/', $cash)) {
                return false;
            }

            if (isset($isIn)) {
                $transaction = new Transaction();
                $transaction->setUser($user);
                $transaction->setDate(new \DateTime('now'));
                $transaction->setCashIn($cash);
                $transaction->setTotal($sum[0]['result'] + $cash);
            }

            if (isset($isOut)) {
                if ($sum[0]['result'] - $cash < 0) {
                    return false;
                } else {
                    $transaction = new Transaction();
                    $transaction->setUser($user);
                    $transaction->setDate(new \DateTime('now'));
                    $transaction->setCashOut($cash);
                    $transaction->setTotal($sum[0]['result'] - $cash);
                }
            }

            $em->persist($transaction);
            $em->flush();

            $jsonData = array(
                'user_id' => $transaction->getUser()->getId(),
                'user_account' => $transaction->getUser()->getAccount(),
                'cashIn' => $transaction->getCashIn(),
                'cashOut' => $transaction->getCashOut(),
                'total' => $transaction->getTotal(),
                'date' => $transaction->getDate()->format('Y-m-d H:i:s'),
            );

            return new JsonResponse($jsonData);
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
            $jsonData = array();

            $em = $this->getDoctrine()->getManager();
            $user = $em->find('AppBundle\Entity\User', $id);

            $transactionQuery = $em->createQueryBuilder()
                ->select('t')
                ->from('AppBundle\Entity\Transaction', 't')
                ->where('t.user = :user')
                ->andwhere('t.date like :date')
                ->setParameter('user', $user)
                ->setParameter('date', $date.'%');
            $transactions = $em->getRepository(Transaction::class)->page($transactionQuery, $page);

            foreach ($transactions['result'] as $transaction) {
                $temp = array(
                    'cashIn' => $transaction->getCashIn(),
                    'cashOut' => $transaction->getCashOut(),
                    'total' => $transaction->getTotal(),
                    'date' => $transaction->getDate()->format('Y-m-d H:i:s'),
                );

                $jsonData[] = $temp;
            }

            return new JsonResponse($jsonData);
        } else {
            return $this->render('search.html.twig');
        }
    }
}
