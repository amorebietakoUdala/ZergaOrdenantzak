<?php

namespace App\Controller;

use App\Entity\Ordenantza;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Ordenantzaparrafoa;
use App\Form\OrdenantzaparrafoaType;
use App\Repository\OrdenantzaRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Ordenantzaparrafoa controller.
 *
 * @Route("/admin/ordenantzaparrafoa")
 */
class OrdenantzaparrafoaController extends AbstractController
{

    public $em;
    public $ordenantzaRepo;

    public function __construct(EntityManagerInterface $em, OrdenantzaRepository $ordenantzaRepo)
    {
        $this->em = $em;
        $this->ordenantzaRepo = $ordenantzaRepo;
    }

    /**
     * @Route("/up/{id}", name="admin_ordenantzaparrafoa_up")
     * @Method("GET")
     */
    public function upAction(Request $request, Ordenantzaparrafoa $op)
    {
        $op->setOrdena($op->getOrdena() - 1);
        $this->em->persist($op);
        $this->em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/down/{id}", name="admin_ordenantzaparrafoa_down")
     * @Method("GET")
     */
    public function downAction(Request $request, Ordenantzaparrafoa $op)
    {

        $op->setOrdena($op->getOrdena() + 1);
        $this->em->persist($op);
        $this->em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
    
    /**
     * Creates a new Ordenantzaparrafoa entity.
     *
     * @Route("/new/{ordenantzaid}", name="admin_ordenantzaparrafoa_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $ordenantzaid)
    {
        $ordenantzaparrafoa = new Ordenantzaparrafoa();
        $ordenantza = $this->ordenantzaRepo->find( $ordenantzaid );
        $ordenantzaparrafoa->setOrdenantza( $ordenantza );
        $ordenantzaparrafoa->setUdala( $this->getUser()->getUdala() );

        $form = $this->createForm(OrdenantzaparrafoaType::class, $ordenantzaparrafoa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    
            $this->em->persist($ordenantzaparrafoa);
            $this->em->flush();

            return $this->redirect( $request->headers->get( 'referer' ) . '#ordenantzaparrafoa'.$ordenantzaparrafoa->getId());
        } 
        
        return $this->render('ordenantzaparrafoa/new.html.twig', array(
            'ordenantzaparrafoa' => $ordenantzaparrafoa,
            'ordenantzaid' => $ordenantzaid,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/ezabatu/{id}", name="admin_ordenantzaparrafoa_ezabatu")
     * @Method("GET")
     */
    public function ezabatuAction(Ordenantzaparrafoa $ordenantzaparrafoa)
    {
        $deleteForm = $this->createDeleteForm($ordenantzaparrafoa);

        return $this->render('ordenantzaparrafoa/_ordenantzaparrafoadeleteform.html.twig', array(            
            'delete_form' => $deleteForm->createView(),
            'id' => $ordenantzaparrafoa->getId()
        ));
    }

    /**
     * Deletes a Ordenantzaparrafoa entity.
     *
     * @Route("/{id}", name="admin_ordenantzaparrafoa_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Ordenantzaparrafoa $ordenantzaparrafoa)
    {
        $form = $this->createDeleteForm($ordenantzaparrafoa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    
            $this->em->remove($ordenantzaparrafoa);
            $this->em->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Creates a form to delete a Ordenantzaparrafoa entity.
     *
     * @param Ordenantzaparrafoa $ordenantzaparrafoa The Ordenantzaparrafoa entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ordenantzaparrafoa $ordenantzaparrafoa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_ordenantzaparrafoa_delete', array('id' => $ordenantzaparrafoa->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
