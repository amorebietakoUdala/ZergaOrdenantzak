<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Azpiatala;
use App\Form\AzpiatalaType;
use App\Repository\AtalaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Azpiatala controller.
 */
#[Route(path: '/admin/azpiatala')]
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
     */
    #[Route(path: '/new/{atalaid}', options: ['expose' => true], name: 'admin_azpiatala_new', methods: ['GET', 'POST'])]
    public function new(Request $request, $atalaid)
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

    
    #[Route(path: '/ezabatu/{id}', options: ['expose' => true], name: 'admin_azpiatala_ezabatu', methods: ['GET'])]
    public function ezabatu(Azpiatala $azpiatala): Response
    {

        $deleteForm = $this->createDeleteForm($azpiatala);

        return $this->render('azpiatala/_azpiataladeleteform.html.twig', array(
            'delete_form' => $deleteForm->createView(),
            'id' => $azpiatala->getId()
        ));
    }

    /**
     * Deletes a Azpiatala entity.
     */
    #[Route(path: '/{id}', name: 'admin_azpiatala_delete', methods: ['DELETE'])]
    public function delete(Request $request, Azpiatala $azpiatala): RedirectResponse
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
     * @return Form The form
     */
    private function createDeleteForm(Azpiatala $azpiatala)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_azpiatala_delete', array('id' => $azpiatala->getId())))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
