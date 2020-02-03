<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\RegistrationFormType;
use App\Form\Type\PasswordFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/utilisateur/creation", name="app_register")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = bin2hex(random_bytes(10));

            $email = (new TemplatedEmail())
                ->from('register@oxyjeune.fr')
                ->to('jkenobi@free.fr')
//                ->to($user->getEmail())
                ->subject('Création du compte Oxyjeune')
                ->htmlTemplate('user/email_confirmation.html.twig')
                ->context([
                    'username' => $user,
                    'password' => $password,
                ]);
            $mailer->send($email);

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $password
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/mot_de_passe_perdu", name="userExist")
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function userExistAction(Request $request, MailerInterface $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $repository = $this->getDoctrine()->getRepository(User::class);
            $email = $user->getEmail();
            $verif = $repository->findOneByEmail($email);
            $username = $repository->findOneByEmail($email);
            if ($verif != null) {
                if ($form->isSubmitted()) {
                    $token = bin2hex(random_bytes(10));
                    $email = (new TemplatedEmail())
                        ->from('register@oxyjeune.fr')
                        ->to('jkenobi@free.fr')
//                ->to($user->getEmail())
                        ->subject('Oxyjeune | Mot de passe oublié')
                        ->htmlTemplate('user/email_lost_password.html.twig')
                        ->context([
                            'username' => $username,
                            'token' => $token,
                        ]);
                    $mailer->send($email);
                    $username->setToken($token);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($username);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_login');
                }
            } else {
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('user/lost_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reinitialisation/{email}/{token}", name="resestPassword")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function resestPasswordAction(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $token = $request->get('token');
        $repository = $this->getDoctrine()->getRepository(User::class);
        $tokenBDD = $repository->findOneByToken($token);
        if ($tokenBDD != null) {
            $email = $request->get('email');
            $user = $repository->findOneByEmail($email);
            $form = $this->createForm(PasswordFormType::class, $user);
            if ($request->isMethod('POST')) {
                $form->handleRequest($request);
                $password = $user->getPassword();
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $password
                    )
                );
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_login');
            }

            return $this->render('user/reset_password.html.twig', [
                'form' => $form->createView(),
            ]);

        }else{
            return "erreur";
        }
    }

}