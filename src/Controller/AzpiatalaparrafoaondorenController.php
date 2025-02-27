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
 */
#[Route(path: 'admin/azpiatalaparrafoaondoren')]
class AzpiatalaparrafoaondorenController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em, 
        private readonly AzpiatalaparrafoaondorenRepository $apoRepo, 
        private readonly AzpiatalaRepository $azpiatalaRepo, 
    )
    {
    }

    #[Route(path: '/up/{id}', name: 'admin_azpiatalaparrafoaondoren_up', methods: ['GET'])]
    public function up(Request $request, Azpiatalaparrafoaondoren $op): RedirectResponse
    {
        $op->setOrdena($op->getOrdena() - 1);
        $this->em->persist($op);
        $this->em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route(path: '/down/{id}', name: 'admin_azpiatalaparrafoaondoren_down', methods: ['GET'])]
    public function down(Request $request, Azpiatalaparrafoaondoren $op): RedirectResponse
    {
        $op->setOrdena($op->getOrdena() + 1);
        $this->em->persist($op);
        $this->em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
    /**
     * Lists all azpiatalaparrafoaondoren entities.
     */
    #[Route(path: '/', name: 'admin_azpiatalaparrafoaondoren_index', methods: ['GET'])]
    public function index(): Response
    {

        $azpiatalaparrafoaondorens = $this->apoRepo->findAll();

        return $this->render('azpiatalaparrafoaondoren/index.html.twig', ['azpiatalaparrafoaondorens' => $azpiatalaparrafoaondorens]);
    }

    /**
     * Creates a new azpiatalaparrafoaondoren entity.
     */
    #[Route(path: '/new/{azpiatalaid}', options: ['expose' => true], name: 'admin_azpiatalaparrafoaondoren_new', methods: ['GET', 'POST'])]
    public function new(Request $request, $azpiatalaid )
    {
        $azpiatala = $this->azpiatalaRepo->find( $azpiatalaid );
        $azpiatalaparrafoaondoren = new Azpiatalaparrafoaondoren();
        $azpiatalaparrafoaondoren->setAzpiatala( $azpiatala );
        /** @var User $user */
        $user = $this->getUser();
        $azpiatalaparrafoaondoren->setUdala( $user->getUdala() );

        $form = $this->createForm(AzpiatalaparrafoaondorenType::class, $azpiatalaparrafoaondoren);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($azpiatalaparrafoaondoren);
            $this->em->flush($azpiatalaparrafoaondoren);

            return $this->redirect( $request->headers->get( 'referer' ) . '#azpiatalaparrafoaondoren'.$azpiatalaparrafoaondoren->getId());
        }

        return $this->render('azpiatalaparrafoaondoren/new.html.twig', ['azpiatalaparrafoaondoren' => $azpiatalaparrafoaondoren, 'azpiatalaid'       => $azpiatalaid, 'form' => $form->createView()]);
    }

    
    #[Route(path: '/ezabatu/{id}', options: ['expose' => true], name: 'admin_azpiatalaparrafoaondoren_ezabatu', methods: ['GET'])]
    public function ezabatu(Azpiatalaparrafoaondoren $azpiatalaparrafoaondoren): Response
    {
        $deleteForm = $this->createDeleteForm($azpiatalaparrafoaondoren);

        return $this->render('azpiatalaparrafoaondoren/_azpiatalaparrafoaondorendeleteform.html.twig', ['delete_form' => $deleteForm->createView(), 'id' => $azpiatalaparrafoaondoren->getId()]);
    }

    /**
     * Deletes a azpiatalaparrafoaondoren entity.
     */
    #[Route(path: '/{id}', name: 'admin_azpiatalaparrafoaondoren_delete', methods: ['DELETE'])]
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
            ->setAction($this->generateUrl('admin_azpiatalaparrafoaondoren_delete', ['id' => $azpiatalaparrafoaondoren->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm()
        ;
    }
}
