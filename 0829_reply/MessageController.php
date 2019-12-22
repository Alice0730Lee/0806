<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Reply;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;

class MessageController extends AbstractController
{
    /**
      * @Route("/show", name="app_show", methods={"GET"})
      */
    public function show(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        
        $query = $em->createQueryBuilder()
                ->select('u')
                ->from('App\Entity\User', 'u')
                ->getQuery();

        $r_query = $em->createQueryBuilder()
                    ->select('u.id, r') //u.id在第一維度陣列，r在第二維度陣列
                    ->from('App\Entity\User', 'u')
                    ->leftjoin( //以user為主，合併表
                        'App\Entity\Reply', 'r',
                        \Doctrine\ORM\Query\Expr\Join::WITH,
                        'u.id = r.uid'
                        )
                    ->orderby('u.id, r.rid') //排序
                    ->getQuery();
        $reply = $r_query->getResult();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('/post.html.twig', [
            'pagination' => $pagination,
            'reply' => $reply
        ]);
    }

    /**
      * @Route("/create", name="app_create", methods={"POST"})
      */
    public function create(Request $request)
    {
        $name =$request->get('name');
        $con =$request->get('con');

        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setName($name);
        $user->setContent($con);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_show');
    }

    /**
      * @Route("/edit", name="app_edit", methods={"PUT"})
      */
    public function edit(Request $request)
    {
        $btnE = $request->get("btnE");//原始留言編輯
        $btnR = $request->get("btnR");//回覆
        $btnRE = $request->get("btnRE");//回覆留言編輯
        $id = $request->get("id");

        $entityManager = $this->getDoctrine()->getManager();
        $qb = $entityManager->createQueryBuilder();

        if (isset($btnE)) {
            $query = $qb->select('u')
                        ->from('App\Entity\User', 'u')
                        ->where('u.id = :id')
                        ->setParameter('id', $id)
                        ->getQuery();
            $user = $query->execute();

            return $this->render('/edit.html.twig', [
                'user' => $user[0],
            ]);
        }

        if (isset($btnR)) {
            $query = $qb->select('u')
                        ->from('App\Entity\User', 'u')
                        ->where('u.id = :id')
                        ->setParameter('id', $id)
                        ->getQuery();
            $user = $query->execute();

            return $this->render('/reply.html.twig', [
                'user' => $user[0],
            ]);
        }

        if (isset($btnRE)) {
            $query = $qb->select('r')
                        ->from('App\Entity\Reply', 'r')
                        ->where('r.rid = :id')
                        ->setParameter('id', $id)
                        ->getQuery();
            $reply = $query->execute();

            return $this->render('/redit.html.twig', [
                'reply' => $reply[0],
            ]);
        }
    }

    /**
      * @Route("/update", name="app_update", methods={"PUT"})
      */
    public function update(Request $request)
    {
        $btnCancel = $request->get("btnCancel");
        $btnUpdate = $request->get("btnUpdate");
        $btnRUpdate = $request->get("btnRUpdate");
        $newContent = $request->get("upCon");
        $id = $request->get("upId");

        $entityManager = $this->getDoctrine()->getManager();
        $qb = $entityManager->createQueryBuilder();

        if (isset($btnCancel)) {
            return $this->redirectToRoute('app_show');
        }

        if (isset($btnUpdate)) {
            $query = $qb->update('App\Entity\User', 'u')
                    ->set('u.content', ':content')
                    ->where('u.id = :id')
                    ->setParameter('content', $newContent)
                    ->setParameter('id', $id)
                    ->getQuery();
            $query->execute();

            return $this->redirectToRoute('app_show');
        }

        if (isset($btnRUpdate)) {
            $query = $qb->update('App\Entity\Reply', 'r')
                    ->set('r.r_content', ':content')
                    ->where('r.rid = :id')
                    ->setParameter('content', $newContent)
                    ->setParameter('id', $id)
                    ->getQuery();
            $query->execute();

            return $this->redirectToRoute('app_show');
        }
    }

    /**
      * @Route("/delete", name="app_delete", methods={"DELETE"})
      */
    public function delete(Request $request )
    {
        $btnD = $request->get("btnD");
        $btnRD = $request->get("btnRD");
        $id = $request->get("id");

        $entityManager = $this->getDoctrine()->getManager();
        $qb = $entityManager->createQueryBuilder();

        if (isset($btnD)) {
            $query = $qb->delete('App\Entity\User', 'u')
                    ->where('u.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery();
            $query->execute();

            $r_query = $qb->delete('App\Entity\Reply', 'r')
                    ->where('r.uid = :id')
                    ->setParameter('id', $id)
                    ->getQuery();
            $r_query->execute();
        }

        if (isset($btnRD)) {
            $r_query = $qb->delete('App\Entity\Reply', 'r')
                    ->where('r.rid = :id')
                    ->setParameter('id', $id)
                    ->getQuery();
            $r_query->execute();
        }

        return $this->redirectToRoute('app_show');
    }

    /**
      * @Route("/reply", name="app_reply", methods={"POST"})
      */
      public function reply(Request $request)
    {
        $btnCancel = $request->get("btnCancel");
        $btnReply = $request->get("btnReply");

        $entityManager = $this->getDoctrine()->getManager();

        if (isset($btnCancel)) {
            return $this->redirectToRoute('app_show');
        }

        if (isset($btnReply)) {
            $name = $request->get('reName');
            $con = $request->get('reCon');
            $uid = $request->get('uid');

            $reply = new Reply();
            $reply->setName($name);
            $reply->setContent($con);
            $reply->setUid($uid);
            $entityManager->persist($reply);
            $entityManager->flush();

        return $this->redirectToRoute('app_show');
        }
    }
}
