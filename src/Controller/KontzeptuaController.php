<?php

namespace App\Controller;

use App\Repository\AzpiatalaRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Kontzeptua;
use App\Form\KontzeptuaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

    /**
     * Kontzeptua controller.
     */
    #[Route(path: '/admin/kontzeptua')]
    class KontzeptuaController extends AbstractController
    {

        public function __construct(
            private readonly EntityManagerInterface $em, 
            private readonly AzpiatalaRepository $azpiatalaRepo
        )
        {
        }

        /**
         * Creates a new Kontzeptua entity.
         */
        #[Route(path: '/new/{azpiatalaid}', options: ['expose' => true], name: 'admin_kontzeptua_new', methods: ['GET', 'POST'])]
        public function new ( Request $request, $azpiatalaid )
        {
            $azpiatala = $this->azpiatalaRepo->find( $azpiatalaid );
            $kontzeptua = new Kontzeptua();
            $kontzeptua->setAzpiatala( $azpiatala );
            /** @var User $user */
            $user = $this->getUser();
            $kontzeptua->setUdala( $user->getUdala() );

            $form = $this->createForm( KontzeptuaType::class, $kontzeptua );
            $form->handleRequest( $request );

            if ( $form->isSubmitted() && $form->isValid() ) {
                    $this->em->persist( $kontzeptua );
                $this->em->flush();

                return $this->redirect( $request->headers->get( 'referer' ) . '#kontzeptua'.$kontzeptua->getId());

            }

            return $this->render(
                'kontzeptua/new.html.twig',
                ['kontzeptua'  => $kontzeptua, 'azpiatalaid' => $azpiatalaid, 'form'        => $form->createView()]
            );
        }

        
        #[Route(path: '/{id}/edit/{ordenantzaid}', options: ['expose' => true], name: 'admin_kontzeptua_edit', methods: ['GET', 'POST'])]
        public function edit(Request $request, Kontzeptua $kontzeptua, $ordenantzaid)
        {
            $deleteForm = $this->createDeleteForm($kontzeptua);
            $editForm = $this->createForm(KontzeptuaType::class, $kontzeptua);
            $editForm->handleRequest($request);
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                    $this->em->persist($kontzeptua);
                $this->em->flush();

                return $this->redirect( $request->headers->get( 'referer' ) . '#kontzeptua'.$kontzeptua->getId());
            }
            return $this->render('kontzeptua/edit.html.twig', ['kontzeptua' => $kontzeptua, 'ordenantzaid' => $ordenantzaid, 'form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);

        }


        
        #[Route(path: '/ezabatu/{id}', options: ['expose' => true], name: 'admin_kontzeptua_ezabatu', methods: ['GET'])]
        public function ezabatu(Kontzeptua $kontzeptua): Response
        {

            $deleteForm = $this->createDeleteForm($kontzeptua);

            return $this->render('kontzeptua/_kontzeptuadeleteform.html.twig', ['delete_form' => $deleteForm->createView(), 'id' => $kontzeptua->getId()]);
        }
        
        /**
         * Deletes a Kontzeptua entity.
         */
        #[Route(path: '/{id}', name: 'admin_kontzeptua_delete', methods: ['DELETE'])]
        public function delete ( Request $request, Kontzeptua $kontzeptua ): RedirectResponse
        {
            $form = $this->createDeleteForm( $kontzeptua );
            $form->handleRequest( $request );

            if ( $form->isSubmitted() && $form->isValid() ) {
                    $this->em->remove( $kontzeptua );
                $this->em->flush();
            }

            return $this->redirect( $request->headers->get( 'referer' ) );
        }

        /**
         * Creates a form to delete a Kontzeptua entity.
         *
         * @param Kontzeptua $kontzeptua The Kontzeptua entity
         *
         * @return Form The form
         */
        private function createDeleteForm ( Kontzeptua $kontzeptua )
        {
            return $this->createFormBuilder()
                ->setAction( $this->generateUrl( 'admin_kontzeptua_delete', ['id' => $kontzeptua->getId()] ) )
                ->setMethod( Request::METHOD_DELETE )
                ->getForm();
        }
    }
