<?php
namespace App\Controller;

use App\Entity\Heure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{

    /**
     * @Route("/", name="index")
     * @Security("is_granted('ROLE_USER')")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(Heure::class);
        $heures = $repository->findHeuresByUser($user);
        return $this->render('index/index.html.twig', ['heures' => $heures]);
    }

}