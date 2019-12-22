<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Reply;
use Doctrine\ORM\emInterface;
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
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->findAll();
        $replyRepository = $this->getDoctrine()->getRepository(Reply::class);
        $reply = $replyRepository->findAll();

        $pagination = $paginator->paginate(
            $user,
            $request->query->getInt('page', 1),
            10
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
        $content =$request->get('con');

        $em = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setName($name);
        $user->setContent($content);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_show');
    }

    /**
      * @Route("/edit", name="app_edit", methods={"PUT"})
      */
    public function edit(Request $request)
    {
        $isEdit = $request->get("btnE");
        $isReply = $request->get("btnR");
        $isReplyEdit = $request->get("btnRE");
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();

        if (isset($isEdit)) {
            $user = $em->find('App\Entity\User', $id);
            $em->persist($user);
            $em->flush();

            return $this->render('/edit.html.twig', [
                'user' => $user,
            ]);
        }

        if (isset($isReply)) {
            $user = $em->find('App\Entity\User', $id);
            $em->persist($user);
            $em->flush();

            return $this->render('/reply.html.twig', [
                'user' => $user,
            ]);
        }

        if (isset($isReplyEdit)) {
            $reply = $em->find('App\Entity\Reply', $id);
            $em->persist($reply);
            $em->flush();

            return $this->render('/redit.html.twig', [
                'reply' => $reply,
            ]);
        }
    }

    /**
      * @Route("/update", name="app_update", methods={"PUT"})
      */
    public function update(Request $request)
    {
        $isCancel = $request->get("btnCancel");
        $isUpdate = $request->get("btnUpdate");
        $isReplyUpdate = $request->get("btnRUpdate");
        $newContent = $request->get("upCon");
        $id = $request->get("upId");

        $em = $this->getDoctrine()->getManager();

        if (isset($isCancel)) {
            return $this->redirectToRoute('app_show');
        }

        if (isset($isUpdate)) {
            $user = $em->find('App\Entity\User', $id);
            $user->setContent($newContent);
            $user->setDate(new \DateTime("now"));
            $em->flush();

            return $this->redirectToRoute('app_show');
        }

        if (isset($isReplyUpdate)) {
            $reply = $em->find('App\Entity\Reply', $id);
            $reply->setContent($newContent);
            $reply->setDate(new \DateTime("now"));
            $em->flush();

            return $this->redirectToRoute('app_show');
        }
    }

    /**
      * @Route("/delete", name="app_delete", methods={"DELETE"})
      */
    public function delete(Request $request)
    {
        $isDelete = $request->get("btnD");
        $isReplyDelete = $request->get("btnRD");
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        if (isset($isDelete)) {
            $query = $qb->delete('App\Entity\Reply', 'r')
                    ->where('r.user = :id')
                    ->setParameter('id', $id)
                    ->getQuery();
            $query->execute();

            $user = $em->find('App\Entity\User', $id);
            $em->remove($user);
            $em->flush();
        }

        if (isset($isReplyDelete)) {
            $reply = $em->find('App\Entity\Reply', $id);
            $em->remove($reply);
            $em->flush();
        }

        return $this->redirectToRoute('app_show');
    }

    /**
      * @Route("/reply", name="app_reply", methods={"POST"})
      */
      public function reply(Request $request)
    {
        $isCancel = $request->get("btnCancel");
        $isReply = $request->get("btnReply");

        $em = $this->getDoctrine()->getManager();

        if (isset($isCancel)) {
            return $this->redirectToRoute('app_show');
        }

        if (isset($isReply)) {
            $name = $request->get('reName');
            $content = $request->get('reCon');
            $uid = $request->get('uid');
            $user = $em->find('App\Entity\User', $uid);

            $reply = new Reply();
            $reply->setName($name);
            $reply->setContent($content);
            $reply->setUser($user);
            $em->persist($reply);
            $em->flush();

            return $this->redirectToRoute('app_show');
        }
    }
}
