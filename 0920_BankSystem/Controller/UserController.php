<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * 首頁
     *
     * @Route("/show", name="show", methods={"GET"})
     */
    public function showAction()
    {
        return $this->render('show.html.twig', [
            'text' => '輸入使用者代號'
        ]);
    }

    /**
     * 判別資料庫內有無使用者代號
     *
     * @Route("/do", name="do", methods={"POST"})
     */
    public function doAction(Request $request)
    {
        $isLogIn = $request->get('isLogIn');
        $isCancel = $request->get('isCancel');
        $isJoin = $request->get('isJoin');
        $account = $request->get('account');

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);

        $user = $repository->findOneBy(
            array('account' => $account)
        );

        if ($account == '') {
            return $this->render('show.html.twig', [
                'text' => '代號不得為空值'
            ]);
        }

        if (isset($isLogIn)) {
            if ($user == NULL) {
                return $this->render('show.html.twig', [
                    'text' => '查無此代號，請重新輸入'
                ]);
            }

            return $this->render('do.html.twig', [
                'account' => $account,
                'id' => $user->getId()
            ]);
        }

        if (isset($isJoin)) {
            if ($user != NULL) {
                return $this->render('show.html.twig', [
                   'text' => '代號已被申請，請重新輸入'
               ]);
            }

            $user = new User();
            $user->setAccount($account);
            $em->persist($user);
            $em->flush();

            return $this->render('show.html.twig', [
                'text' => '申請成功，請再次輸入代號'
            ]);
        }
    }
}
