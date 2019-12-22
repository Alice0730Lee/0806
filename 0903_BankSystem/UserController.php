<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/index", methods={"GET"})
     */
    public function index()
    {
        return 登入頁面
    }

    /**
      * @Route("/logIn", methods={"POST"})
      */
    public function logIn()
    {
        驗證是否登入成功
        return 選取存提款頁面
    }

    /**
      * @Route("/do", methods={"POST"})
      */
    public function do()
    {
        選擇存款提款或查詢
        return 到選擇的function
    }
}
