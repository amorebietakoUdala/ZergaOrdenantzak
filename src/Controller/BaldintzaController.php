<?php

namespace App\Controller;


use App\Entity\Baldintza;
use App\Form\BaldintzaType;
use App\Repository\BaldintzaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Baldintza controller.
 */
#[Route(path: '/{_locale}/admin/baldintza')]
class BaldintzaController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em, 
        private readonly BaldintzaRepository $baldintzaRepo, 
    )
    {
    }

    /**
     * Lists all baldintza entities.
     */
    #[Route(path: '/', name: 'baldintza_index', methods: ['GET'])]
    public function index(): Response
    {
        $baldintzas = $this->baldintzaRepo->findAll();

        /** @var Baldintza $baldintza **/
        $baldintza = new Baldintza();
        /** @var User $user */
        $user = $this->getUser();
        $baldintza->setUdala($user->getUdala());
        $form = $this->createForm(BaldintzaType::class, $baldintza,['action' => $this->generateUrl('baldintza_new'), 'method' => 'POST']);

        $deleteForms = [];
        foreach ( $baldintzas as $baldintza ) {
            $deleteForms[ $baldintza->getId() ] = $this->createDeleteForm( $baldintza )->createView();
        }
        return $this->render('baldintza/index.html.twig', ['baldintzas' => $baldintzas, 'deleteforms' => $deleteForms, 'form' => $form->createView()]);
    }

    /**
     * Creates a new baldintza entity.
     */
    #[Route(path: '/new', name: 'baldintza_new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $baldintza = new Baldintza();
        $form = $this->createForm(BaldintzaType::class, $baldintza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->em->persist($baldintza);
            $this->em->flush($baldintza);

            return $this->redirectToRoute('baldintza_index');
        }

        return $this->render('baldintza/new.html.twig', ['baldintza' => $baldintza, 'form' => $form->createView()]);
    }



    /**
     * Displays a form to edit an existing baldintza entity.
     */
    #[Route(path: '/{id}/edit', options: ['expose' => true], name: 'baldintza_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Baldintza $baldintza)
    {
        $deleteForm = $this->createDeleteForm($baldintza);
        $editForm = $this->createForm(BaldintzaType::class, $baldintza);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('baldintza_index');
        }

        return $this->render('baldintza/edit.html.twig', ['baldintza' => $baldintza, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView()]);
    }

    /**
     * Deletes a baldintza entity.
     */
    #[Route(path: '/{id}', name: 'baldintza_delete', methods: ['DELETE'])]
    public function delete(Request $request, Baldintza $baldintza): RedirectResponse
    {
        $form = $this->createDeleteForm($baldintza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->em->remove($baldintza);
            $this->em->flush($baldintza);
        }

        return $this->redirectToRoute('baldintza_index');
    }

    /**
     * Creates a form to delete a baldintza entity.
     *
     * @param Baldintza $baldintza The baldintza entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Baldintza $baldintza)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('baldintza_delete', ['id' => $baldintza->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
