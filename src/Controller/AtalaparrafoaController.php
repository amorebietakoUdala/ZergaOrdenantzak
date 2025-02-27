<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Atalaparrafoa;
use App\Form\AtalaparrafoaType;
use App\Repository\AtalaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Atalaparrafoa controller.
 */
#[Route(path: '/admin/atalaparrafoa')]
class AtalaparrafoaController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em, 
        private readonly AtalaRepository $atalaRepo
    )
    {
    }

    #[Route(path: '/up/{id}', name: 'admin_atalaparrafoa_up', methods: ['GET'])]
    public function up(Request $request, Atalaparrafoa $op): RedirectResponse
    {
        $op->setOrdena($op->getOrdena() - 1);
        $this->em->persist($op);
        $this->em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route(path: '/down/{id}', name: 'admin_atalaparrafoa_down', methods: ['GET'])]
    public function down(Request $request, Atalaparrafoa $op): RedirectResponse
    {
        $op->setOrdena($op->getOrdena() + 1);
        $this->em->persist($op);
        $this->em->flush();

        return $this->redirect($request->headers->get('referer'));
    }


    /**
     * Creates a new Atalaparrafoa entity.
     */
    #[Route(path: '/new/{atalaid}', options: ['expose' => true], name: 'admin_atalaparrafoa_new', methods: ['GET', 'POST'])]
    public function new(Request $request, $atalaid)
    {

        $atala = $this->atalaRepo->find( $atalaid );
        if (!$atala) {
            throw new NotFoundHttpException( "Ez da aurkitu" );
        }
        $atalaparrafoa = new Atalaparrafoa();

        $atalaparrafoa->setAtala( $atala );
        /** @var User $user */
        $user = $this->getUser();
        $atalaparrafoa->setUdala($user->getUdala());

        $form = $this->createForm(AtalaparrafoaType::class, $atalaparrafoa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($atalaparrafoa);
            $this->em->flush();

            return $this->redirect( $request->headers->get( 'referer' ) . '#atalaparrafoa'.$atalaparrafoa->getId());

        }

        return $this->render('atalaparrafoa/new.html.twig', ['atalaparrafoa' => $atalaparrafoa, 'atalaid' => $atalaid, 'form' => $form->createView()]);
    }

    
    #[Route(path: '/ezabatu/{id}', options: ['expose' => true], name: 'admin_atalaparrafoa_ezabatu', methods: ['GET'])]
    public function ezabatu(Atalaparrafoa $atalaparrafoa): Response
    {

        $deleteForm = $this->createDeleteForm($atalaparrafoa);

        return $this->render('atalaparrafoa/_atalaparrafoadeleteform.html.twig', ['delete_form' => $deleteForm->createView(), 'id' => $atalaparrafoa->getId()]);
    }

    /**
     * Deletes a Atalaparrafoa entity.
     */
    #[Route(path: '/{id}', name: 'admin_atalaparrafoa_delete', methods: ['DELETE'])]
    public function delete(Request $request, Atalaparrafoa $atalaparrafoa): RedirectResponse
    {
        $form = $this->createDeleteForm($atalaparrafoa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $this->em->remove($atalaparrafoa);
            $this->em->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Creates a form to delete a Atalaparrafoa entity.
     *
     * @param Atalaparrafoa $atalaparrafoa The Atalaparrafoa entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Atalaparrafoa $atalaparrafoa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_atalaparrafoa_delete', ['id' => $atalaparrafoa->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
