<?php

namespace App\Controller;

use App\Entity\Azpiatalaparrafoaondoren;
use App\Form\AzpiatalaparrafoaondorenType;
use App\Repository\AzpiatalaparrafoaondorenRepository;
use App\Repository\AzpiatalaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Azpiatalaparrafoaondoren controller.
 *
 * @Route("admin/azpiatalaparrafoaondoren")
 */
class AzpiatalaparrafoaondorenController extends AbstractController
{

    private $em;
    private $apoRepo;
    private $azpiatalaRepo;

    public function __construct(EntityManagerInterface $em, AzpiatalaparrafoaondorenRepository $apoRepo, AzpiatalaRepository $azpiatalaRepo)
    {
        $this->em = $em;
        $this->apoRepo = $apoRepo;
        $this->azpiatalaRepo = $azpiatalaRepo;
    }

    /**
     * @Route("/up/{id}", name="admin_azpiatalaparrafoaondoren_up", methods={"GET"})
     */
    public function up(Request $request, Azpiatalaparrafoaondoren $op): RedirectResponse
    {
        $op->setOrdena($op->getOrdena() - 1);
        $this->em->persist($op);
        $this->em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/down/{id}", name="admin_azpiatalaparrafoaondoren_down", methods={"GET"})
     */
    public function down(Request $request, Azpiatalaparrafoaondoren $op): RedirectResponse
    {
        $op->setOrdena($op->getOrdena() + 1);
        $this->em->persist($op);
        $this->em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
    /**
     * Lists all azpiatalaparrafoaondoren entities.
     *
     * @Route("/", name="admin_azpiatalaparrafoaondoren_index", methods={"GET"})
     */
    public function index(): Response
    {

        $azpiatalaparrafoaondorens = $this->apoRepo->findAll();

        return $this->render('azpiatalaparrafoaondoren/index.html.twig', array(
            'azpiatalaparrafoaondorens' => $azpiatalaparrafoaondorens,
        ));
    }

    /**
     * Creates a new azpiatalaparrafoaondoren entity.
     *
     * @Route("/new/{azpiatalaid}", options={"expose"=true}, name="admin_azpiatalaparrafoaondoren_new", methods={"GET", "POST"})
     */
    public function new(Request $request, $azpiatalaid )
    {
        $em = $this->getDoctrine();

        $azpiatala = $this->azpiatalaRepo->find( $azpiatalaid );
        $azpiatalaparrafoaondoren = new Azpiatalaparrafoaondoren();
        $azpiatalaparrafoaondoren->setAzpiatala( $azpiatala );
        $azpiatalaparrafoaondoren->setUdala( $this->getUser()->getUdala() );

        $form = $this->createForm(AzpiatalaparrafoaondorenType::class, $azpiatalaparrafoaondoren);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($azpiatalaparrafoaondoren);
            $this->em->flush($azpiatalaparrafoaondoren);

            return $this->redirect( $request->headers->get( 'referer' ) . '#azpiatalaparrafoaondoren'.$azpiatalaparrafoaondoren->getId());
        }

        return $this->render('azpiatalaparrafoaondoren/new.html.twig', array(
            'azpiatalaparrafoaondoren' => $azpiatalaparrafoaondoren,
            'azpiatalaid'       => $azpiatalaid,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/ezabatu/{id}", options={"expose"=true}, name="admin_azpiatalaparrafoaondoren_ezabatu", methods={"GET"})
     */
    public function ezabatu(Azpiatalaparrafoaondoren $azpiatalaparrafoaondoren): Response
    {
        $deleteForm = $this->createDeleteForm($azpiatalaparrafoaondoren);

        return $this->render('azpiatalaparrafoaondoren/_azpiatalaparrafoaondorendeleteform.html.twig', array(
            'delete_form' => $deleteForm->createView(),
            'id' => $azpiatalaparrafoaondoren->getId()
        ));
    }

    /**
     * Deletes a azpiatalaparrafoaondoren entity.
     *
     * @Route("/{id}", name="admin_azpiatalaparrafoaondoren_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Azpiatalaparrafoaondoren $azpiatalaparrafoaondoren): RedirectResponse
    {
        $form = $this->createDeleteForm($azpiatalaparrafoaondoren);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($azpiatalaparrafoaondoren);
            $this->em->flush($azpiatalaparrafoaondoren);
        }

        return $this->redirect( $request->headers->get( 'referer' ) );
    }

    /**
     * Creates a form to delete a azpiatalaparrafoaondoren entity.
     *
     * @param Azpiatalaparrafoaondoren $azpiatalaparrafoaondoren The azpiatalaparrafoaondoren entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Azpiatalaparrafoaondoren $azpiatalaparrafoaondoren)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_azpiatalaparrafoaondoren_delete', array('id' => $azpiatalaparrafoaondoren->getId())))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
