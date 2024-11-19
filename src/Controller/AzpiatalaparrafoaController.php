<?php

namespace App\Controller;

use App\Entity\Azpiatala;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Azpiatalaparrafoa;
use App\Form\AzpiatalaparrafoaType;
use App\Repository\AzpiatalaRepository;
use Doctrine\ORM\EntityManagerInterface;

    /**
     * Azpiatalaparrafoa controller.
     *
     * @Route("/admin/azpiatalaparrafoa")
     */
    class AzpiatalaparrafoaController extends AbstractController
    {

        private $em;
        private $azpiatalaRepo;

        public function __construct(EntityManagerInterface $em, AzpiatalaRepository $azpiatalaRepo)
        {
            $this->em = $em;
            $this->azpiatalaRepo = $azpiatalaRepo;
        }
        /**
         * @Route("/up/{id}", name="admin_azpiatalaparrafoa_up")
         * @Method("GET")
         */
        public function upAction(Request $request, Azpiatalaparrafoa $op)
        {
            $op->setOrdena($op->getOrdena() - 1);
            $this->em->persist($op);
            $this->em->flush();

            return $this->redirect($request->headers->get('referer'));
        }

        /**
         * @Route("/down/{id}", name="admin_azpiatalaparrafoa_down")
         * @Method("GET")
         */
        public function downAction(Request $request, Azpiatalaparrafoa $op)
        {
            $op->setOrdena($op->getOrdena() + 1);
            $this->em->persist($op);
            $this->em->flush();

            return $this->redirect($request->headers->get('referer'));
        }
        
        /**
         * Creates a new Azpiatalaparrafoa entity.
         *
         * @Route("/new/{azpiatalaid}", options = { "expose" = true }, name="admin_azpiatalaparrafoa_new")
         * @Method({"GET", "POST"})
         */
        public function newAction ( Request $request, $azpiatalaid )
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
                array (
                    'azpiatalaparrafoa' => $azpiatalaparrafoa,
                    'azpiatalaid'       => $azpiatalaid,
                    'form'              => $form->createView(),
                )
            );
        }

        /**
         *
         * @Route("/ezabatu/{id}", options = { "expose" = true }, name="admin_azpiatalaparrafoa_ezabatu")
         * @Method("GET")
         */
        public function ezabatuAction(Azpiatalaparrafoa $azpiatalaparrafoa)
        {
            $deleteForm = $this->createDeleteForm($azpiatalaparrafoa);

            return $this->render('azpiatalaparrafoa/_azpiatalaparrafoadeleteform.html.twig', array(
                'delete_form' => $deleteForm->createView(),
                'id' => $azpiatalaparrafoa->getId()
            ));
        }
        
        /**
         * Deletes a Azpiatalaparrafoa entity.
         *
         * @Route("/{id}", name="admin_azpiatalaparrafoa_delete")
         * @Method("DELETE")
         */
        public function deleteAction ( Request $request, Azpiatalaparrafoa $azpiatalaparrafoa )
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
         * @return \Symfony\Component\Form\Form The form
         */
        private function createDeleteForm ( Azpiatalaparrafoa $azpiatalaparrafoa )
        {
            return $this->createFormBuilder()
                ->setAction(
                    $this->generateUrl( 'admin_azpiatalaparrafoa_delete', array ('id' => $azpiatalaparrafoa->getId()) )
                )
                ->setMethod( 'DELETE' )
                ->getForm();
        }
    }
