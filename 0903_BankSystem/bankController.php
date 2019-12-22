<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/save", methods={"POST"})
     */
    public function save()
    {
        檢查欄位是否為數值
            preg_match("/^\d+\.?\d{0,4}$/"
        儲存金額
        return 成功頁面
    }

    /**
      * @Route("/take", methods={"POST"})
      */
    public function take()
    {
        檢查數值是否大於餘額
        提取金額

        if (isset($isSearch)) {
          return $this->render('io.html.twig', [
              'p' => '{{ path("search") }}',
              'btn' => 'isSearch',
              'value' => '查詢日期',
              'id' => $id
          ]);
      }
        return 成功頁面
    }

    /**
      * @Route("/search", methods={"POST"})
      */
    public function search()
    {
        查詢結果
    }
}