<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Udala;
use App\Form\UdalaType;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Udala controller.
 *
 * @Route("/{_locale}/admin/udala")
 */
class UdalaController extends AbstractController
{
    private $em;

    public function __construct( EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Lists all Udala entities.
     *
     * @Route("/", defaults={"page"=1}, name="udala_index", methods={"GET"})
     * @Route("/page{page}", name="udala_index_paginated", methods={"GET"})
     */
    public function index($page)
    {
        if ($this->isGranted('ROLE_SUPER_ADMIN'))
        {
            
            $udalas = $this->em->getRepository(Udala::class)->findAll();

            $adapter = new ArrayAdapter($udalas);
            $pagerfanta = new Pagerfanta($adapter);

            $deleteForms = array();
            foreach ($udalas as $udala) {
                $deleteForms[$udala->getId()] = $this->createDeleteForm($udala)->createView();
            }

            try {
                $entities = $pagerfanta
                    // Le nombre maximum d'éléments par page
    //                ->setMaxPerPage($this->getUser()->getUdala()->getOrrikatzea())
                    ->setMaxPerPage('25')
                    // Notre position actuelle (numéro de page)
                    ->setCurrentPage($page)
                    // On récupère nos entités via Pagerfanta,
                    // celui-ci s'occupe de limiter la requête en fonction de nos réglages.
                    ->getCurrentPageResults()
                ;
            } catch (\Pagerfanta\Exception\NotValidCurrentPageException $e) {
                throw $this->createNotFoundException("Orria ez da existitzen");
            }

            return $this->render('udala/index.html.twig', array(
    //            'udalas' => $udalas,
                'udalas' => $entities,
                'deleteforms' => $deleteForms,
                'pager' => $pagerfanta,
            ));
        }else if ($this->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute('udala_show', array('id' => $this->getUser()->getUdala()->getId()));
        }else
        {
//            return $this->redirectToRoute('backend_errorea');
            return $this->redirectToRoute('ordenantza_index');
        }

    }

    /**
     * Creates a new Udala entity.
     *
     * @Route("/new", name="udala_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            $udala = new Udala();
            $form = $this->createForm(UdalaType::class, $udala);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($udala);
                $this->em->flush();

                return $this->redirectToRoute('udala_show', array('id' => $udala->getId()));
            }

            return $this->render('udala/new.html.twig', array(
                'udala' => $udala,
                'form' => $form->createView(),
            ));
        }else
        {
            return $this->redirectToRoute('backend_errorea');
        }
    }

    /**
     * Finds and displays a Udala entity.
     *
     * @Route("/{id}", name="udala_show", methods={"GET"})
     */
    public function show(Udala $udala): Response
    {
        $deleteForm = $this->createDeleteForm($udala);

        return $this->render('udala/show.html.twig', array(
            'udala' => $udala,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Udala entity.
     *
     * @Route("/{id}/edit", name="udala_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Udala $udala)
    {
        $deleteForm = $this->createDeleteForm($udala);
        $editForm = $this->createForm(UdalaType::class, $udala);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
                        $this->em->persist($udala);
            $this->em->flush();

            return $this->redirectToRoute('udala_edit', array('id' => $udala->getId()));
        }

        return $this->render('udala/edit.html.twig', array(
            'udala' => $udala,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Udala entity.
     *
     * @Route("/{id}", name="udala_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Udala $udala): RedirectResponse
    {
        $form = $this->createDeleteForm($udala);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                        $this->em->remove($udala);
            $this->em->flush();
        }

        return $this->redirectToRoute('udala_index');
    }

    /**
     * Creates a form to delete a Udala entity.
     *
     * @param Udala $udala The Udala entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Udala $udala)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('udala_delete', array('id' => $udala->getId())))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
