<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Atalaparrafoa;
use App\Form\AtalaparrafoaType;
use App\Repository\AtalaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Atalaparrafoa controller.
 *
 * @Route("/admin/atalaparrafoa")
 */
class AtalaparrafoaController extends AbstractController
{

    private $em;
    private $atalaRepo;

    public function __construct(EntityManagerInterface $em, AtalaRepository $atalaRepo)
    {
        $this->em = $em;
        $this->atalaRepo = $atalaRepo;
    }

    /**
     * @Route("/up/{id}", name="admin_atalaparrafoa_up", methods={"GET"})
     */
    public function up(Request $request, Atalaparrafoa $op): RedirectResponse
    {
        $op->setOrdena($op->getOrdena() - 1);
        $this->em->persist($op);
        $this->em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/down/{id}", name="admin_atalaparrafoa_down", methods={"GET"})
     */
    public function down(Request $request, Atalaparrafoa $op): RedirectResponse
    {
        $op->setOrdena($op->getOrdena() + 1);
        $this->em->persist($op);
        $this->em->flush();

        return $this->redirect($request->headers->get('referer'));
    }


    /**
     * Creates a new Atalaparrafoa entity.
     *
     * @Route("/new/{atalaid}", options={"expose"=true}, name="admin_atalaparrafoa_new", methods={"GET", "POST"})
     */
    public function new(Request $request, $atalaid)
    {

        $atala = $this->atalaRepo->find( $atalaid );
        if (!$atala) {
            throw new NotFoundHttpException( "Ez da aurkitu" );
        }
        $atalaparrafoa = new Atalaparrafoa();

        $atalaparrafoa->setAtala( $atala );
        $atalaparrafoa->setUdala($this->getUser()->getUdala());

        $form = $this->createForm(AtalaparrafoaType::class, $atalaparrafoa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($atalaparrafoa);
            $this->em->flush();

            return $this->redirect( $request->headers->get( 'referer' ) . '#atalaparrafoa'.$atalaparrafoa->getId());

        }

        return $this->render('atalaparrafoa/new.html.twig', array(
            'atalaparrafoa' => $atalaparrafoa,
            'atalaid' => $atalaid,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/ezabatu/{id}", options={"expose"=true}, name="admin_atalaparrafoa_ezabatu", methods={"GET"})
     */
    public function ezabatu(Atalaparrafoa $atalaparrafoa): Response
    {

        $deleteForm = $this->createDeleteForm($atalaparrafoa);

        return $this->render('atalaparrafoa/_atalaparrafoadeleteform.html.twig', array(
            'delete_form' => $deleteForm->createView(),
            'id' => $atalaparrafoa->getId()
        ));
    }

    /**
     * Deletes a Atalaparrafoa entity.
     *
     * @Route("/{id}", name="admin_atalaparrafoa_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Atalaparrafoa $atalaparrafoa): RedirectResponse
    {
        $form = $this->createDeleteForm($atalaparrafoa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($atalaparrafoa);
            $this->em->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Creates a form to delete a Atalaparrafoa entity.
     *
     * @param Atalaparrafoa $atalaparrafoa The Atalaparrafoa entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Atalaparrafoa $atalaparrafoa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_atalaparrafoa_delete', array('id' => $atalaparrafoa->getId())))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
