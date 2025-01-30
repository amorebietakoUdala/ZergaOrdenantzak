<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Azpiatalaparrafoa;
use App\Form\AzpiatalaparrafoaType;
use App\Repository\AzpiatalaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

    /**
     * Azpiatalaparrafoa controller.
     */
    #[Route(path: '/admin/azpiatalaparrafoa')]
    class AzpiatalaparrafoaController extends AbstractController
    {

        public function __construct(
            private readonly EntityManagerInterface $em, 
            private readonly AzpiatalaRepository $azpiatalaRepo
        )
        {
        }
        
        #[Route(path: '/up/{id}', name: 'admin_azpiatalaparrafoa_up', methods: ['GET'])]
        public function up(Request $request, Azpiatalaparrafoa $op): RedirectResponse
        {
            $op->setOrdena($op->getOrdena() - 1);
            $this->em->persist($op);
            $this->em->flush();

            return $this->redirect($request->headers->get('referer'));
        }

        #[Route(path: '/down/{id}', name: 'admin_azpiatalaparrafoa_down', methods: ['GET'])]
        public function down(Request $request, Azpiatalaparrafoa $op): RedirectResponse
        {
            $op->setOrdena($op->getOrdena() + 1);
            $this->em->persist($op);
            $this->em->flush();

            return $this->redirect($request->headers->get('referer'));
        }
        
        /**
         * Creates a new Azpiatalaparrafoa entity.
         */
        #[Route(path: '/new/{azpiatalaid}', options: ['expose' => true], name: 'admin_azpiatalaparrafoa_new', methods: ['GET', 'POST'])]
        public function new ( Request $request, $azpiatalaid )
        {
            $azpiatala = $this->azpiatalaRepo->find( $azpiatalaid );
            $azpiatalaparrafoa = new Azpiatalaparrafoa();
            $azpiatalaparrafoa->setAzpiatala( $azpiatala );
            $azpiatalaparrafoa->setUdala( $this->getUser()->getUdala() );

            $form = $this->createForm( AzpiatalaparrafoaType::class, $azpiatalaparrafoa );
            $form->handleRequest( $request );

            if ( $form->isSubmitted() && $form->isValid() ) {
                    $this->em->persist( $azpiatalaparrafoa );
                $this->em->flush();

                return $this->redirect( $request->headers->get( 'referer' ) . '#azpiatalaparrafoa'.$azpiatalaparrafoa->getId());
            }

            return $this->render(
                'azpiatalaparrafoa/new.html.twig',
                ['azpiatalaparrafoa' => $azpiatalaparrafoa, 'azpiatalaid'       => $azpiatalaid, 'form'              => $form->createView()]
            );
        }

        
        #[Route(path: '/ezabatu/{id}', options: ['expose' => true], name: 'admin_azpiatalaparrafoa_ezabatu', methods: ['GET'])]
        public function ezabatu(Azpiatalaparrafoa $azpiatalaparrafoa): Response
        {
            $deleteForm = $this->createDeleteForm($azpiatalaparrafoa);

            return $this->render('azpiatalaparrafoa/_azpiatalaparrafoadeleteform.html.twig', ['delete_form' => $deleteForm->createView(), 'id' => $azpiatalaparrafoa->getId()]);
        }
        
        /**
         * Deletes a Azpiatalaparrafoa entity.
         */
        #[Route(path: '/{id}', name: 'admin_azpiatalaparrafoa_delete', methods: ['DELETE','POST'])]
        public function delete ( Request $request, Azpiatalaparrafoa $azpiatalaparrafoa ): RedirectResponse
        {
            $form = $this->createDeleteForm( $azpiatalaparrafoa );
            $form->handleRequest( $request );

            if ( $form->isSubmitted() && $form->isValid() ) {
                    $this->em->remove( $azpiatalaparrafoa );
                $this->em->flush();
            }

            return $this->redirect( $request->headers->get( 'referer' ) );
        }

        /**
         * Creates a form to delete a Azpiatalaparrafoa entity.
         *
         * @param Azpiatalaparrafoa $azpiatalaparrafoa The Azpiatalaparrafoa entity
         *
         * @return Form The form
         */
        private function createDeleteForm ( Azpiatalaparrafoa $azpiatalaparrafoa )
        {
            return $this->createFormBuilder()
                ->setAction(
                    $this->generateUrl( 'admin_azpiatalaparrafoa_delete', ['id' => $azpiatalaparrafoa->getId()] )
                )
                ->setMethod( Request::METHOD_DELETE )
                ->getForm();
        }
    }
