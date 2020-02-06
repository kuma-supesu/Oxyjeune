<?php
namespace App\Controller;

use App\Entity\Tableau;
use App\Entity\TableauLigne;
use App\Entity\TableauPaiement;
use App\Form\Type\TableauLigneType;
use App\Form\Type\TableauPaiementType;
use App\Form\Type\TableauType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TableauController extends AbstractController
{

    /**
     * @Route("/tableau", name="tableauListe")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function tableauListeAction()
    {
        $repository = $this->getDoctrine()->getRepository(tableau::class);
        $tableau = $repository->findAll();
        return $this->render('tableau/list.html.twig', ['tableau' => $tableau]);
    }

    /**
     * @Route("/tableau/info/{tableau}", name="tableauInfo")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function tableauInfoAction(Request $request)
    {
        $keyTableau = $request->get('tableau');
        $repository = $this->getDoctrine()->getRepository(tableau::class);
        $tableau = $repository->findOneById($keyTableau);
        return $this->render('tableau/info.html.twig', ['tableau' => $tableau]);
    }

    /**
     * @Route("/tableau/info/{tableau}/ligne/{ligne}", name="tableauLigneInfo")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function tableauLigneInfoAction(Request $request)
    {
        $keyTableau = $request->get('tableau');
        $keyLigne = $request->get('ligne');
        $repository = $this->getDoctrine()->getRepository(tableauLigne::class);
        $tableauLigne = $repository->findOneById($keyLigne);
        return $this->render('tableauLigne/info.html.twig', ['data' => $tableauLigne, 'tableau' => $keyTableau]);
    }

    /**
     * @Route("/tableau/creation", name="tableauCreation")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function tableauCreationAction(Request $request)
    {
        $data = new Tableau();
        $form = $this->createForm(TableauType::class, $data);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->flushToDB($data);
                $keyTableau = $data->getId();
                return $this->redirectToRoute('tableauInfo', ['tableau' => $keyTableau]);
            }
        }
        return $this->render('tableau/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tableau/insertion/{tableau}", name="tableauLigneInsertion")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function tableauLigneInsertionAction(Request $request)
    {
        $keyTableau = $request->get('tableau');
        $data = new TableauLigne();
        $repository = $this->getDoctrine()->getRepository(tableau::class);
        $tableau = $repository->findOneById($keyTableau);
        $form = $this->createForm(TableauLigneType::class, $data);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data->setTableau($tableau);
                $this->flushToDB($data);
                return $this->redirectToRoute('tableauInfo', ['tableau' => $keyTableau]);
            }
        }
        return $this->render('tableauLigne/insert.html.twig', ['form' => $form->createView(),'tableau' => $keyTableau]);
    }

    /**
     * @Route("/tableauLigne/{tableau}/insertion/{ligne}", name="tableauPaiementInsertion")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function tableauPaiementInsertionAction(Request $request)
    {
        $keyLigne = $request->get('ligne');
        $keyTableau = $request->get('tableau');
        $data = new TableauPaiement();
        $repository = $this->getDoctrine()->getRepository(tableauLigne::class);
        $tableauLigne = $repository->findOneById($keyLigne);
        $form = $this->createForm(TableauPaiementType::class, $data);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data->setTableauLigne($tableauLigne);
                $this->flushToDB($data);
                return $this->redirectToRoute('tableauLigneInfo', ['tableau' => $keyTableau, 'ligne' => $keyLigne]);
            }
        }
        return $this->render('tableauPaiement/insert.html.twig', ['form' => $form->createView(), 'tableau' => $keyTableau, 'ligne' => $keyLigne]);
    }

    /**
     * @Route("/tableau/modification/{tableau}", name="tableauModification")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function tableauModificationAction(Request $request)
    {
        $keyTableau = $request->get('tableau');
        $repository = $this->getDoctrine()->getRepository(tableau::class);
        $data = $repository->findOneById($keyTableau);
        $form = $this->createForm(tableauType::class, $data);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->flushToDB($data);
                return $this->redirectToRoute('tableauInfo', ['tableau' => $keyTableau]);
            }
        }
        return $this->render('tableau/modify.html.twig', ['form' => $form->createView(),'tableau' => $keyTableau]);
    }

    /**
     * @Route("/tableau/modification/{tableau}/ligne/{ligne}", name="tableauLigneModification")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function tableauLigneModificationAction(Request $request)
    {
        $keyTableau = $request->get('tableau');
        $keyLigne = $request->get('ligne');
        $repository = $this->getDoctrine()->getRepository(tableauLigne::class);
        $data = $repository->findOneById($keyLigne);
        $repository = $this->getDoctrine()->getRepository(tableau::class);
        $tableau = $repository->findOneById($keyTableau);
        $form = $this->createForm(tableauLigneType::class, $data);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data->setTableau($tableau);
                $this->flushToDB($data);
                return $this->redirectToRoute('tableauLigneInfo', ['tableau' => $keyTableau, 'ligne' => $keyLigne] );
            }
        }
        return $this->render('tableauLigne/modify.html.twig', ['form' => $form->createView(), 'tableau' => $keyTableau, 'ligne' => $keyLigne]);
    }

    /**
     * @Route("/tableau/suppression/{tableau}", name="tableauSuppression")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param $request
     * @return RedirectResponse
     */
    public function suppressionTableauAction(Request $request)
    {
        $keyTableau = $request->get('tableau');
        $repository = $this->getDoctrine()->getRepository(tableau::class);
        $tableau = $repository->findOneById($keyTableau);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($tableau);
        $entityManager->flush();
        return $this->redirectToRoute('tableauListe');
    }

    /**
     * @Route("/tableau/suppression/{tableau}/ligne/{ligne}", name="tableauLigneSuppression")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param $request
     * @return RedirectResponse
     */
    public function suppressionTableauLigneAction(Request $request)
    {
        $keyLigne = $request->get('ligne');
        $keyTableau = $request->get('tableau');
        $repository = $this->getDoctrine()->getRepository(tableauLigne::class);
        $tableauLigne = $repository->findOneById($keyLigne);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($tableauLigne);
        $entityManager->flush();
        return $this->redirectToRoute('tableauInfo', ['tableau' => $keyTableau]);
    }

    /**
     * @Route("/tableau/suppression/{tableau}/ligne/{ligne}/paiement/{paiement}", name="tableauPaiementSuppression")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param $request
     * @return RedirectResponse
     */
    public function suppressionTableauPaiementAction(Request $request)
    {
        $keyLigne = $request->get('ligne');
        $keyTableau = $request->get('tableau');
        $keyPaiement = $request->get('paiement');
        $repository = $this->getDoctrine()->getRepository(tableauPaiement::class);
        $tableauPaiement = $repository->findOneById($keyPaiement);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($tableauPaiement);
        $entityManager->flush();
        return $this->redirectToRoute('tableauLigneInfo', ['tableau' => $keyTableau, 'ligne' => $keyLigne]);
    }

    public function flushToDB($data)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
    }

}