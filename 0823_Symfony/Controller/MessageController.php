<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * @Route("/show", methods={"GET"})
     */
    public function show()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $qb = $entityManager->createQueryBuilder();
        
        $query = $qb->select('u')
                    ->from('App\Entity\User', 'u')
                    ->getQuery();
        $users = $query->execute();

        return $this->render('/post.html.twig', [
            'users' => $users
        ]);
    }

    /**
      * @Route("/create", methods={"POST"})
      */
    public function create()
    {
        $name = $_POST["name"];
        $con = $_POST["con"];

        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setName($name);
        $user->setContent($con);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_show');
    }

    /**
      * @Route("/edit", methods={"POST"})
      */
    public function edit()
    {
        $id = $_POST["id"];

        $entityManager = $this->getDoctrine()->getManager();
        $qb = $entityManager->createQueryBuilder();

        if (isset($_POST["btnD"])) {
            $query = $qb->delete('App\Entity\User', 'u')
                        ->where('u.id = ?1')
                        ->setParameter(1, $id)
                        ->getQuery();
            $query->execute();

            return $this->redirectToRoute('app_show');
        }
        if (isset($_POST["btnUp"])) {
            $query = $qb->select('u')
                        ->from('App\Entity\User', 'u')
                        ->where('u.id = ?1')
                        ->setParameter(1, $id)
                        ->getQuery();
            $user = $query->execute();

            return $this->render('/edit.html.twig', [
                'user' => $user[0],
                'id' => $id,
            ]);
        }
    }

    /**
      * @Route("/update", methods={"POST"})
      */
    public function update()
    {
        $id = $_POST["upId"];
        $newContent = $_POST["upCon"];

        $entityManager = $this->getDoctrine()->getManager();
        $qb = $entityManager->createQueryBuilder();

        if (isset($_POST["btnCancel"])) {
            return $this->redirectToRoute('app_show');
        }
        if (isset($_POST["btnUpdate"])) {
            $query = $qb->update('App\Entity\User', 'u')
                    ->set('u.content', '?1')
                    ->where('u.id = ?2')
                    ->setParameter(1, $newContent)
                    ->setParameter(2, $id)
                    ->getQuery();
            $query->execute();

            return $this->redirectToRoute('app_show');
        }
    }
}
