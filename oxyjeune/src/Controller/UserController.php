<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\RegistrationFormType;
use App\Form\Type\PasswordFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

    /**
     * @Route("/users", name="userListe")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function userListeAdminAction()
    {
        $repository = $this->getDoctrine()->getRepository(user::class);
        $users = $repository->findAll();
        return $this->render('user/list.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/user/info/{slug}", name="userInfo")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function userInfoAdminAction(Request $request)
    {
        $id = $request->get('slug');
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneById($id);
        return $this->render('user/info.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/user", name="user")
     * @Security("is_granted('ROLE_USER')")
     * @return Response
     */
    public function userAction()
    {
        $session = new Session();
        $id = $this->getUser()->getId();
        $email = $this->getUser()->getEmail();
        $session->set('id', $id);
        $session->set('email', $email);
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneById($id);
        return $this->render('user/info.html.twig', ['user' => $user ]);
    }

    /**
     * @Route("/user/creation", name="userCreate")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function UserCreateAdminAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer): Response
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

            return $this->redirectToRoute('userListe');
        }

        return $this->render('user/create.html.twig', [
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
     * @Route("/token", name="userToken")
     * @Security("is_granted('ROLE_USER')")
     * @return Response
     * @throws \Exception
     */
    public function userTokenAction()
    {
        $session = new Session();
        $email = $session->get('email');
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneByEmail($email);
        $token = bin2hex(random_bytes(10));
        $user->setToken($token);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('resetPassword', ['email' => $email, 'token' => $token]);
    }

    /**
     * @Route("/admin/token/{slug}", name="adminToken")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function adminTokenAction(Request $request)
    {
        $email = $request->get('slug');
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneByEmail($email);
        $token = bin2hex(random_bytes(10));
        $user->setToken($token);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('resetPassword', ['email' => $email, 'token' => $token]);
    }

    /**
     * @Route("/reinitialisation/{email}/{token}", name="resetPassword")
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
                return $this->redirectToRoute('index');
            }

            return $this->render('user/reset_password.html.twig', [
                'form' => $form->createView(),
            ]);

        }else{
            return $this->render('user/error.html.twig');
        }
    }

    /**
     * @Route("/user/modification", name="userModification")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @return Response
     */
    public function userModificationAction(Request $request)
    {
        $session = new Session();
        $id = $session->get('id');
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneById($id);
        $form = $this->createForm(RegistrationFormType::class, $user);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('user' );
            }
        }
        return $this->render('user/modify.html.twig', ['form' => $form->createView(), 'slug' => $id]);
    }

    /**
     * @Route("/user/modification/{slug}", name="userModificationAdmin")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function userModificationAdminAction(Request $request)
    {
        $id = $request->get('slug');
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneById($id);
        $form = $this->createForm(RegistrationFormType::class, $user);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('userInfo', ['slug' => $id] );
            }
        }
        return $this->render('user/modify.html.twig', ['form' => $form->createView(), 'slug' => $id]);
    }

    /**
     * @Route("/user/suppression/{slug}", name="userSuppression")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return RedirectResponse
     */
    public function userSuppressionAdminAction(Request $request)
    {
        $id = $request->get('slug');
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneById($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('userListe');
    }

}