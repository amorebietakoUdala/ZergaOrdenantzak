<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Azpiatala;
use App\Form\AzpiatalaType;
use App\Repository\AtalaRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Azpiatala controller.
 *
 * @Route("/admin/azpiatala")
 */
class AzpiatalaController extends AbstractController
{

    private $em;
    private $atalaRepo;

    public function __construct(EntityManagerInterface $em, AtalaRepository $atalaRepo)
    {
        $this->em = $em;
        $this->atalaRepo = $atalaRepo;
    }

    /**
     * Creates a new Azpiatala entity.
     *
     * @Route("/new/{atalaid}", options = { "expose" = true }, name="admin_azpiatala_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $atalaid)
    {

        $atala = $this->atalaRepo->find( $atalaid );
        $azpiatala = new Azpiatala();
        $azpiatala->setAtala( $atala );
        $azpiatala->setUdala( $this->getUser()->getUdala() );
        
        $form = $this->createForm(AzpiatalaType::class, $azpiatala);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($azpiatala);
            $this->em->flush();

            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('azpiatala/new.html.twig', array(
            'azpiatala' => $azpiatala,
            'atalaid' => $atalaid,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/ezabatu/{id}", options = { "expose" = true }, name="admin_azpiatala_ezabatu")
     * @Method("GET")
     */
    public function ezabatuAction(Azpiatala $azpiatala)
    {

        $deleteForm = $this->createDeleteForm($azpiatala);

        return $this->render('azpiatala/_azpiataladeleteform.html.twig', array(
            'delete_form' => $deleteForm->createView(),
            'id' => $azpiatala->getId()
        ));
    }

    /**
     * Deletes a Azpiatala entity.
     *
     * @Route("/{id}", name="admin_azpiatala_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Azpiatala $azpiatala)
    {
        $form = $this->createDeleteForm($azpiatala);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->remove($azpiatala);
            $this->em->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Creates a form to delete a Azpiatala entity.
     *
     * @param Azpiatala $azpiatala The Azpiatala entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Azpiatala $azpiatala)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_azpiatala_delete', array('id' => $azpiatala->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}