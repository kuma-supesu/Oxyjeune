<?php
namespace App\Controller;

use App\Entity\Planning;
use App\Entity\Journee;
use App\Entity\User;
use App\Form\Type\PlanningType;
use App\Form\Type\JourneeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlanningController extends AbstractController
{

    /**
     * @Route("/planning", name="planningListe")
     * @Security("is_granted('ROLE_USER')")
     */
    public function planningListeAction()
    {
        $repository = $this->getDoctrine()->getRepository(Planning::class);
        $planning = $repository->findAll();
        return $this->render('planning/list.html.twig', ['planning' => $planning]);
    }

    /**
     * @Route("/planning/info/{slug}", name="planningInfo")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @return Response
     */
    public function planningInfoAction(Request $request)
    {
        $id = $request->get('slug');
        $repository = $this->getDoctrine()->getRepository(Planning::class);
        $planning = $repository->findOneById($id);
        return $this->render('planning/info.html.twig', ['planning' => $planning]);
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
                $this->flushToDB($data);
                $id = $data->getId();
                return $this->redirectToRoute('planningInfo',['slug' => $id]);
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
        $id = $request->get('slug');
        $repository = $this->getDoctrine()->getRepository(Planning::class);
        $data = $repository->findOneById($id);
        $form = $this->createForm(PlanningType::class, $data);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $date = $data->getJournees()[0]->getDate();
                $data->setDebut($date);
                $this->flushToDB($data);
                return $this->redirectToRoute('planningInfo', ['slug' => $id] );
            }
        }
        return $this->render('planning/modify.html.twig', ['form' => $form->createView(), 'planning' => $data, 'slug' => $id]);
    }

    /**
     * @Route("/planning/insertion/{slug}", name="planningInsertion")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function planningInsertionnAction(Request $request)
    {
        $data = new Journee();
        $id = $request->get('slug');
        $repository = $this->getDoctrine()->getRepository(Planning::class);
        $planning = $repository->findOneById($id);
        $form = $this->createForm(JourneeType::class, $data);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data->setPlanning($planning);
                $this->flushToDB($data);
                return $this->redirectToRoute('planningInfo', ['slug' => $id] );
            }
        }
        return $this->render('planning/insert.html.twig', ['form' => $form->createView(), 'slug' => $id]);
    }

    /**
     * @Route("/planning/suppression/{slug}", name="planningSuppression")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return RedirectResponse
     */
    public function planningSuppressionAction(Request $request)
    {
        $id = $request->get('slug');
        $repository = $this->getDoctrine()->getRepository(Planning::class);
        $planning = $repository->findOneById($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($planning);
        $entityManager->flush();
        return $this->redirectToRoute('planningListe');
    }

    /**
     * @Route("/planning/date/suppression/{slug}", name="dateSuppression")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return RedirectResponse
     */
    public function dateSuppressionAction(Request $request)
    {
        $id = $request->get('slug');
        $repository = $this->getDoctrine()->getRepository(Journee::class);
        $journee = $repository->findOneById($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($journee);
        $entityManager->flush();
        return $this->redirectToRoute('planningListe');
    }

    /**
     * @Route("/planning/inscription/{slug}/{journee}/{heure}", name="planningInscription")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @return Response
     */
    public function planningInscriptionAction(Request $request)
    {
        $id = $request->get('slug');
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $this->getUser()->getNomComplet();
        $username = $repository->findOneByNomComplet($user);
        $indexJournee = $request->get('journee');
        $indexHeure = $request->get('heure');
        $planning = $this->repositoryFindOneById($id);
        $data = $planning->getJournees()[$indexJournee]->getHeures()[$indexHeure]->addUser($username);
        $this->flushToDB($data);
        return $this->redirectToRoute('planningInfo', ['slug' => $id] );
    }

    /**
     * @Route("/planning/desinscription/{slug}/{journee}/{heure}", name="planningDesinscription")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @return Response
     */
    public function planningDesinscriptionAction(Request $request)
    {
        $id = $request->get('slug');
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $this->getUser()->getNomComplet();
        $username = $repository->findOneByNomComplet($user);
        $indexJournee = $request->get('journee');
        $indexHeure = $request->get('heure');
        $planning = $this->repositoryFindOneById($id);
        $data = $planning->getJournees()[$indexJournee]->getHeures()[$indexHeure]->removeUser($username);
        $this->flushToDB($data);
        return $this->redirectToRoute('planningInfo', ['slug' => $id]);
    }

    private function repositoryFindOneById($id)
    {
        $repository = $this->getDoctrine()->getRepository(Planning::class);
        return $repository->findOneById($id);
    }

    private function flushToDB($data)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
    }

}