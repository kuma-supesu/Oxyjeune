<?php
namespace App\Controller;

use App\Entity\Planning;
use App\Entity\Journee;
use App\Form\Type\PlanningType;
use App\Form\Type\JourneeType;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;

class PlanningController extends AbstractController
{

    /**
     * @Route("/planning", name="planningListe")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function planningListeAction()
    {
        $repository = $this->getDoctrine()->getRepository(Planning::class);
        $planning = $repository->findAll();
        return $this->render('planning/list.html.twig', ['planning' => $planning]);
    }

    /**
     * @Route("/planning/info/{slug}", name="planningInfo")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function planningInfoAction(Request $request)
    {
        $session = new Session();
        $key = $request->get('slug');
        $user[] = $this->getUser()->getNomComplet();
        $date = DateTime::createFromFormat('d-m-Y', $key);
        $repository = $this->getDoctrine()->getRepository(Planning::class);
        $planning = $repository->findOneByDebut($date);
        $id = $planning->getId();
        $session->set('id', $id);
        $session->set('slug', $key);
        return $this->render('planning/info.html.twig', ['planning' => $planning, 'user' => $user]);
    }

    /**
     * @Route("/planning/creation", name="planningCreation")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function planningCreationAction(Request $request)
    {
        $data = new Planning();
        $form = $this->createForm(PlanningType::class, $data);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $date = $data->getJournees()[0]->getDate();
                $data->setDebut($date);
                $this->flushToDB($data);
                $slug = $data->getDebut()->format('d-m-Y');
                return $this->redirectToRoute('planningInfo', ['slug' => $slug] );
            }
        }
        return $this->render('planning/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/planning/modification/{slug}", name="planningModification")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function planningModificationAction(Request $request)
    {
        $session = new Session();
        $id = $session->get('id');
        $repository = $this->getDoctrine()->getRepository(Planning::class);
        $data = $repository->findOneById($id);
        $form = $this->createForm(PlanningType::class, $data);
        $slug = $data->getDebut()->format('d-m-Y');
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $date = $data->getJournees()[0]->getDate();
                $data->setDebut($date);
                $this->flushToDB($data);
                $slug = $data->getDebut()->format('d-m-Y');
                return $this->redirectToRoute('planningInfo', ['slug' => $slug] );
            }
        }
        return $this->render('planning/modify.html.twig', ['form' => $form->createView(), 'slug' => $slug]);
    }

    /**
     * @Route("/planning/insertion/{slug}", name="planningInsertion")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function planningInsertionnAction(Request $request)
    {
        $session = new Session();
        $data = new Journee();
        $id = $session->get('id');
        $repository = $this->getDoctrine()->getRepository(Planning::class);
        $planning = $repository->findOneById($id);
        $slug = $planning->getDebut()->format('d-m-Y');
        $form = $this->createForm(JourneeType::class, $data);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data->setPlanning($planning);
                $this->flushToDB($data);
                $slug = $planning->getDebut()->format('d-m-Y');
                return $this->redirectToRoute('planningInfo', ['slug' => $slug] );
            }
        }
        return $this->render('planning/insert.html.twig', ['form' => $form->createView(), 'slug' => $slug]);
    }

    /**
     * @Route("/planning/suppression/{slug}", name="planningSuppression")
     * @Security("is_granted('ROLE_ADMIN')")
     * @return RedirectResponse
     */
    public function suppressionPlanningAction()
    {
        $session = new Session();
        $id = $session->get('id');
        $repository = $this->getDoctrine()->getRepository(Planning::class);
        $planning = $repository->findOneById($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($planning);
        $entityManager->flush();
        return $this->redirectToRoute('planningListe');
    }

    /**
     * @Route("/planning/inscription/{journee}/{heure}", name="planningInscription")
     * @param Request $request
     * @return Response
     */
    public function planningInscriptionAction(Request $request)
    {
        $session = new Session();
        $id = $session->get('id');
        $slug = $session->get('slug');
        $user[] = $this->getUser()->getNomComplet();
        $indexJournee = $request->get('journee');
        $indexHeure = $request->get('heure');
        $planning = $this->repositoryFindOneById($id);
        $noms = $planning->getJournees()[$indexJournee]->getHeures()[$indexHeure]->getNoms();
        if ($noms != null) {
            $liste = array_merge($user, $noms);
            $data = $planning->getJournees()[$indexJournee]->getHeures()[$indexHeure]->setNoms($liste);
            $this->flushToDB($data);
        } else {
            $data= $planning->getJournees()[$indexJournee]->getHeures()[$indexHeure]->setNoms($user);
            $this->flushToDB($data);
        }
        return $this->redirectToRoute('planningInfo', ['slug' => $slug] );
    }

    /**
     * @Route("/planning/desinscription/{journee}/{heure}", name="planningDesinscription")
     * @param Request $request
     * @return Response
     */
    public function planningDesinscriptionAction(Request $request)
    {
        $session = new Session();
        $id = $session->get('id');
        $slug = $session->get('slug');
        $user = $this->getUser()->getNomComplet();
        $indexJournee = $request->get('journee');
        $indexHeure = $request->get('heure');
        $planning = $this->repositoryFindOneById($id);
        $noms = $planning->getJournees()[$indexJournee]->getHeures()[$indexHeure]->getNoms();
        $search = array_search($user, $noms);
        unset($noms[$search]);
        $data = $planning->getJournees()[$indexJournee]->getHeures()[$indexHeure]->setNoms($noms);
        $this->flushToDB($data);
        return $this->redirectToRoute('planningInfo', ['slug' => $slug]);
    }

    public function repositoryFindOneById($id)
    {
        $repository = $this->getDoctrine()->getRepository(Planning::class);
        return $repository->findOneById($id);
    }

    public function flushToDB($data)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
    }

}