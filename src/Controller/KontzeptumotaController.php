<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Kontzeptumota;
use App\Form\KontzeptumotaType;
use App\Repository\KontzeptumotaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Kontzeptumota controller.
 *
 * @Route("/admin/kontzeptumota")
 */
class KontzeptumotaController extends AbstractController
{

    private $em;
    private $kontzeptumotaRepo;

    public function __construct(EntityManagerInterface $em, KontzeptumotaRepository $kontzeptumotaRepo)
    {
        $this->em = $em;
        $this->kontzeptumotaRepo = $kontzeptumotaRepo;
    }

    /**
     * Lists all Kontzeptumota entities.
     *
     * @Route("/", name="admin_kontzeptumota_index", methods={"GET"})
     */
    public function index(): Response
    {

        $kontzeptumotas = $this->kontzeptumotaRepo->findAll();

        return $this->render('kontzeptumota/index.html.twig', array(
            'kontzeptumotas' => $kontzeptumotas,
        ));
    }

    /**
     * Creates a new Kontzeptumota entity.
     *
     * @Route("/new", name="admin_kontzeptumota_new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
        $kontzeptumotum = new Kontzeptumota();
        $form = $this->createForm(KontzeptumotaType::class, $kontzeptumotum);
        $form->getData()->setUdala($this->getUser()->getUdala());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($kontzeptumotum);
            $this->em->flush();

            return $this->redirectToRoute('admin_kontzeptumota_show', array('id' => $kontzeptumotum->getId()));
        }

        return $this->render('kontzeptumota/new.html.twig', array(
            'kontzeptumotum' => $kontzeptumotum,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Kontzeptumota entity.
     *
     * @Route("/{id}", name="admin_kontzeptumota_show", methods={"GET"})
     */
    public function show(Kontzeptumota $kontzeptumotum): Response
    {
        $deleteForm = $this->createDeleteForm($kontzeptumotum);

        return $this->render('kontzeptumota/show.html.twig', array(
            'kontzeptumotum' => $kontzeptumotum,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Kontzeptumota entity.
     *
     * @Route("/{id}/edit", name="admin_kontzeptumota_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Kontzeptumota $kontzeptumotum)
    {
        $deleteForm = $this->createDeleteForm($kontzeptumotum);
        $editForm = $this->createForm(KontzeptumotaType::class, $kontzeptumotum);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->em->persist($kontzeptumotum);
            $this->em->flush();

            return $this->redirectToRoute('admin_kontzeptumota_edit', array('id' => $kontzeptumotum->getId()));
        }

        return $this->render('kontzeptumota/edit.html.twig', array(
            'kontzeptumotum' => $kontzeptumotum,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Kontzeptumota entity.
     *
     * @Route("/{id}", name="admin_kontzeptumota_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Kontzeptumota $kontzeptumotum): RedirectResponse
    {
        $form = $this->createDeleteForm($kontzeptumotum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($kontzeptumotum);
            $this->em->flush();
        }

        return $this->redirectToRoute('admin_kontzeptumota_index');
    }

    /**
     * Creates a form to delete a Kontzeptumota entity.
     *
     * @param Kontzeptumota $kontzeptumotum The Kontzeptumota entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Kontzeptumota $kontzeptumotum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_kontzeptumota_delete', array('id' => $kontzeptumotum->getId())))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
