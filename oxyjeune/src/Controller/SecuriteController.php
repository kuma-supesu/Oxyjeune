<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecuriteController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login()
    {
        $this->addFlash(
            'success',
            'Vous êtes connecté'
        );
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {

    }
}