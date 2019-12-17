<?php
namespace App\Controller;

use App\Entity\Planning;
use App\Form\Type\CreationPlanningType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/planning/create", name="planningCreate")
     * @param Request $request
     * @return RedirectResponse
     */
    public function planningCreateAction(Request $request)
    {
        $planning = new Planning();

        $form = $this->createForm(CreationPlanningType::class, $planning);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($planning);
                $em->flush($planning);
                return $this->redirectToRoute('Planning_form', array('planningId' =>  $planning->getId()));
            }
        }
        return $this->render('create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/planning", name="planningList")
     */
    public function planningListAction()
    {
        $repository = $this->getDoctrine()->getRepository(Planning::class);
        $planning = $repository->findAllDate();
        return $this->render('list.html.twig', ['data' => $planning]);
    }

    /**
     * @Route("/planning/info/{slug}", name="planningInfo")
     * @param Request $request
     * @return RedirectResponse
     */
    public function planningInfoAction(Request $request)
    {
        $session = new Session();
        $key = $request->get('slug');
        $date = \DateTime::createFromFormat('d-m-Y', $key);
        $repository= $this->getDoctrine()->getRepository(Planning::class);
        $planning = $repository->findByDate($date);
        return $this->render('info.html.twig', ['data' => $planning, 'date' => $date]);
    }

    /**
     * @Route("/planning/inscription/{slug}", name="planningInscription")
     * @param Request $request
     * @return RedirectResponse
     */
    public function planningInscriptionAction(Request $request)
    {
        $planning = new Planning();
        $key = $request->get('slug');
        $date = \DateTime::createFromFormat('Y-m-d', $key);
        $repository= $this->getDoctrine()->getRepository(Planning::class);
        $planning = $repository->findByDate($date);

        $form = $this->createForm(PlanningType::class, $planning);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($planning);
                $em->flush($planning);
                return $this->redirectToRoute('Planning_form', array('planningId' =>  $planning->getId()));
            }
        }
        return $this->render('inscription.html.twig', ['form' => $form->createView()]);
    }

}