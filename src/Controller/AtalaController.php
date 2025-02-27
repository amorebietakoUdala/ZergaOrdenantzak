<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Atala;
use App\Repository\OrdenantzaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Atala controller.
 */
#[Route(path: '/admin/atala')]
class AtalaController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em, 
        private readonly OrdenantzaRepository $ordenantzaRepo
    )
    {
    }

    /**
     * Creates a new Atala entity.
     */
    #[Route(path: '/new/{ordenantzaid}', name: 'admin_atala_new', methods: ['GET', 'POST'])]
    public function new(Request $request, $ordenantzaid)
    {
        $atala = new Atala();
        $ordenantza = $this->ordenantzaRepo->find( $ordenantzaid );
        $atala->setOrdenantza( $ordenantza );
        /** @var User $user */
        $user = $this->getUser();
        $atala->setUdala( $user->getUdala() );
        
        $form = $this->createForm(\App\Form\AtalaType::class, $atala);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($atala);
            $this->em->flush();

            return $this->redirect($request->headers->get('referer'));
        } 

        return $this->render('atala/new.html.twig', ['atala' => $atala, 'ordenantzaid' => $ordenantzaid, 'form' => $form->createView()]);
    }

    
    #[Route(path: '/ezabatu/{id}', options: ['expose' => true], name: 'admin_atala_ezabatu', methods: ['GET'])]
    public function ezabatu(Atala $atala): Response
    {
            
        $deleteForm = $this->createDeleteForm($atala);

        return $this->render('atala/_ataladeleteform.html.twig', ['delete_form' => $deleteForm->createView(), 'id' => $atala->getId()]);
    }
    
    
    /**
     * Deletes a Atala entity.
     */
    #[Route(path: '/{id}', name: 'admin_atala_delete', methods: ['DELETE'])]
    public function delete(Request $request, Atala $atala): RedirectResponse
    {
        $form = $this->createDeleteForm($atala);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* Begiratu ezabatze marka duen (dev) baldin badu, ezabatu
            bestela marka ezarri */
            if ( $atala->getEzabatu() == 1 ) {
                $this->em->remove($atala);
            } else {
                $atala->setEzabatu( 1 );
                $this->em->persist( $atala );
            }

            $this->em->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Creates a form to delete a Atala entity.
     *
     * @param Atala $atala The Atala entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Atala $atala)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_atala_delete', ['id' => $atala->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
