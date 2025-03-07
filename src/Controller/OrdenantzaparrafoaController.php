<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ordenantzaparrafoa;
use App\Form\OrdenantzaparrafoaType;
use App\Repository\OrdenantzaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ordenantzaparrafoa controller.
 */
#[Route(path: '/admin/ordenantzaparrafoa')]
class OrdenantzaparrafoaController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em, 
        private OrdenantzaRepository $ordenantzaRepo
    )
    {
        $this->em = $em;
        $this->ordenantzaRepo = $ordenantzaRepo;
    }

    #[Route(path: '/up/{id}', name: 'admin_ordenantzaparrafoa_up', methods: ['GET'])]
    public function up(Request $request, Ordenantzaparrafoa $op): RedirectResponse
    {
        $op->setOrdena($op->getOrdena() - 1);
        $this->em->persist($op);
        $this->em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route(path: '/down/{id}', name: 'admin_ordenantzaparrafoa_down', methods: ['GET'])]
    public function down(Request $request, Ordenantzaparrafoa $op): RedirectResponse
    {

        $op->setOrdena($op->getOrdena() + 1);
        $this->em->persist($op);
        $this->em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
    
    /**
     * Creates a new Ordenantzaparrafoa entity.
     */
    #[Route(path: '/new/{ordenantzaid}', name: 'admin_ordenantzaparrafoa_new', methods: ['GET', 'POST'])]
    public function new(Request $request, $ordenantzaid)
    {
        $ordenantzaparrafoa = new Ordenantzaparrafoa();
        $ordenantza = $this->ordenantzaRepo->find( $ordenantzaid );
        $ordenantzaparrafoa->setOrdenantza( $ordenantza );
        /** @var User $user */
        $user = $this->getUser();
        $ordenantzaparrafoa->setUdala( $user->getUdala() );

        $form = $this->createForm(OrdenantzaparrafoaType::class, $ordenantzaparrafoa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    
            $this->em->persist($ordenantzaparrafoa);
            $this->em->flush();

            return $this->redirect( $request->headers->get( 'referer' ) . '#ordenantzaparrafoa'.$ordenantzaparrafoa->getId());
        } 
        
        return $this->render('ordenantzaparrafoa/new.html.twig', ['ordenantzaparrafoa' => $ordenantzaparrafoa, 'ordenantzaid' => $ordenantzaid, 'form' => $form->createView()]);
    }

    
    #[Route(path: '/ezabatu/{id}', name: 'admin_ordenantzaparrafoa_ezabatu', methods: ['GET'])]
    public function ezabatu(Ordenantzaparrafoa $ordenantzaparrafoa): Response
    {
        $deleteForm = $this->createDeleteForm($ordenantzaparrafoa);

        return $this->render('ordenantzaparrafoa/_ordenantzaparrafoadeleteform.html.twig', ['delete_form' => $deleteForm->createView(), 'id' => $ordenantzaparrafoa->getId()]);
    }

    /**
     * Deletes a Ordenantzaparrafoa entity.
     */
    #[Route(path: '/{id}', name: 'admin_ordenantzaparrafoa_delete', methods: ['DELETE'])]
    public function delete(Request $request, Ordenantzaparrafoa $ordenantzaparrafoa): RedirectResponse
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
     * @return Form The form
     */
    private function createDeleteForm(Ordenantzaparrafoa $ordenantzaparrafoa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_ordenantzaparrafoa_delete', ['id' => $ordenantzaparrafoa->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
