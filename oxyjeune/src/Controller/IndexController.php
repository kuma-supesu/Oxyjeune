<?php
namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{

    /**
     * @Route("/", name="index")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        return $this->render('index/index.html.twig');
    }

}