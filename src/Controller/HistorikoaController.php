<?php

namespace App\Controller;

use App\Entity\Atalaparrafoa;
use App\Entity\Azpiatala;
use Pagerfanta\Exception\NotValidCurrentPageException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Historikoa;
use App\Entity\Ordenantza;
use App\Entity\Ordenantzaparrafoa;
use App\Form\HistorikoaType;
use App\Repository\HistorikoaRepository;
use App\Repository\OrdenantzaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Qipsius\TCPDFBundle\Controller\TCPDFController;

/**
 * Historikoa controller.
 */
#[Route(path: '/{_locale}/admin/historikoa')]
class HistorikoaController extends AbstractController {

    private $em;
    private $ordenantzaRepo;
    private $historikoaRepo;

    public function __construct(EntityManagerInterface $em, OrdenantzaRepository $ordenantzaRepo, HistorikoaRepository $historikoaRepo) 
    {
        $this->em = $em;
        $this->ordenantzaRepo = $ordenantzaRepo;
        $this->historikoaRepo = $historikoaRepo;
    }

    /**
     * Lists all Historikoa entities.
     */
    #[Route(path: '/', defaults: ['page' => 1], name: 'admin_historikoa_index', methods: ['GET'])]
    #[Route(path: '/page{page}', name: 'admin_historikoa_paginated', methods: ['GET'])]
    public function index($page): Response
    {
        $historikoas = $this->historikoaRepo->findBy([],['id' => 'DESC']);
        $deleteForms = array();
        foreach ($historikoas as $entity)
        {
            $deleteForms[ $entity->getId() ] = $this->createDeleteForm($entity)->createView();
        }
        $adapter = new ArrayAdapter($historikoas);
        $pagerfanta = new Pagerfanta($adapter);
        try
        {
            $entities = $pagerfanta
                ->setMaxPerPage(5)
                ->setCurrentPage($page)
                ->getCurrentPageResults();
        } catch (NotValidCurrentPageException $e)
        {
            throw $this->createNotFoundException("Orria ez da existitzen");
        }

        return $this->render('historikoa/index.html.twig', array(
            'historikoas' => $entities,
            'deleteForms' => $deleteForms,
            'pager'       => $pagerfanta,
        ));
    }

    /**
     * Creates a new Historikoa entity.
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    #[Route(path: '/new', name: 'admin_historikoa_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TCPDFController $tcpdfController)
    {

        $ordenantzas = $this->ordenantzaRepo->findAllOrderByKodea();

        $historikoa = new Historikoa();
        $form = $this->createForm(HistorikoaType::class, $historikoa);
        /** @var User $user */
        $user = $this->getUser();
        $form->getData()->setUdala($user->getUdala());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var Ordenantza $ordenantza */
            foreach ($ordenantzas as $ordenantza)
            {
                $filename = $this->getFilename($user->getUdala()->getKodea(), $ordenantza->getKodea());
                /* Begiratu ezabatze marka duen, baldin badu ezabatu */
                if ($ordenantza->getEzabatu() == 1)
                {
                    $this->em->remove($ordenantza);
                } else
                {
                    /* Historikora pasa, hau da prod eremuetara */
                    $ordenantza->setIzenburuaesProd($ordenantza->getIzenburuaes());
                    $ordenantza->setIzenburuaeuProd($ordenantza->getIzenburuaeu());
                    $ordenantza->setKodeaProd($ordenantza->getKodea());
                    $this->em->persist($ordenantza);
                    foreach ($ordenantza->getParrafoak() as $p)
                    {
                        /** @var Ordenantzaparrafoa  $p */
                        $p->setOrdenaProd($p->getOrdena());
                        $p->setTestuaesProd($p->getTestuaes());
                        $p->setTestuaeuProd($p->getTestuaeu());
                        $this->em->persist($p);
                    }
                    foreach ($ordenantza->getAtalak() as $atala)
                    {
                        if ($atala->getEzabatu() == 1)
                        {
                            $this->em->remove($atala);
                            $this->em->persist($atala);
                        }
                        else
                        {
                            $atala->setIzenburuaeuProd($atala->getIzenburuaeu());
                            $atala->setIzenburuaesProd($atala->getIzenburuaes());
                            $atala->setKodeaProd($atala->getKodea());
                            $atala->setUtsaProd($atala->getUtsa());
                            $this->em->persist($atala);

                            /** @var Atalaparrafoa $atalaparrafoa */
                            foreach ($atala->getParrafoak() as $atalaparrafoa)
                            {
                                if ($atalaparrafoa->getEzabatu() == 1)
                                {
                                    $this->em->remove($atalaparrafoa);
                                    $this->em->persist($atalaparrafoa);
                                } else
                                {
                                    $atalaparrafoa->setOrdenaProd($atalaparrafoa->getOrdena());
                                    $atalaparrafoa->setTestuaesProd($atalaparrafoa->getTestuaes());
                                    $atalaparrafoa->setTestuaeuProd($atalaparrafoa->getTestuaeu());
                                    $this->em->persist($atalaparrafoa);
                                }
                            }

                            /** @var Azpiatala $azpiatala */
                            foreach ($atala->getAzpiatalak() as $azpiatala)
                            {
                                if ($azpiatala->getEzabatu() == 1)
                                {
                                    $this->em->remove($azpiatala);
                                    $this->em->persist($azpiatala);
                                } else
                                {
                                    $azpiatala->setKodeaProd($azpiatala->getKodea());
                                    $azpiatala->setIzenburuaesProd($azpiatala->getIzenburuaes());
                                    $azpiatala->setIzenburuaeuProd($azpiatala->getIzenburuaeu());
                                    $this->em->persist($azpiatala);

                                    foreach ($azpiatala->getParrafoak() as $azpiatalaparrafoa)
                                    {

                                        if ($azpiatalaparrafoa->getEzabatu() == 1)
                                        {
                                            $this->em->remove($azpiatalaparrafoa);
                                            $this->em->persist($azpiatalaparrafoa);
                                        } else
                                        {
                                            $azpiatalaparrafoa->setTestuaesProd($azpiatalaparrafoa->getTestuaes());
                                            $azpiatalaparrafoa->setTestuaeuProd($azpiatalaparrafoa->getTestuaeu());
                                            $azpiatalaparrafoa->setOrdenaProd($azpiatalaparrafoa->getOrdena());
                                            $this->em->persist($azpiatalaparrafoa);
                                        }
                                    }


                                    foreach ($azpiatala->getKontzeptuak() as $kontzeptua)
                                    {

                                        if ($kontzeptua->getEzabatu() == 1)
                                        {
                                            $this->em->remove($kontzeptua);
                                            $this->em->persist($kontzeptua);
                                        } else
                                        {
                                            $kontzeptua->setKodeaProd($kontzeptua->getKodea());
                                            $kontzeptua->setKontzeptuaesProd($kontzeptua->getKontzeptuaes());
                                            $kontzeptua->setKontzeptuaeuProd($kontzeptua->getKontzeptuaeu());
                                            $kontzeptua->setKopuruaProd($kontzeptua->getKopurua());
                                            $kontzeptua->setUnitateaProd($kontzeptua->getUnitatea());
                                            $this->em->persist($kontzeptua);
                                        }
                                    }

                                    foreach ($azpiatala->getParrafoakondoren() as $azpiatalaparrafoa)
                                    {

                                        if ($azpiatalaparrafoa->getEzabatu() == 1)
                                        {
                                            $this->em->remove($azpiatalaparrafoa);
                                            $this->em->persist($azpiatalaparrafoa);
                                        } else
                                        {
                                            $azpiatalaparrafoa->setTestuaesProd($azpiatalaparrafoa->getTestuaes());
                                            $azpiatalaparrafoa->setTestuaeuProd($azpiatalaparrafoa->getTestuaeu());
                                            $azpiatalaparrafoa->setOrdenaProd($azpiatalaparrafoa->getOrdena());
                                            $this->em->persist($azpiatalaparrafoa);
                                        }
                                    }

                                }
                            }
                        }
                    }
                }
                $this->em->flush();

            }

            /* PDF Fitxategia sortuko dugu*/
            /** @var \TCPDF $pdf */
            $pdf = $tcpdfController->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // This is not working in current version of TCPDF
            //$pdf->footerTitle = $form["indarreandata"]->getData()->format('Y/m/d');


            $pdf->SetAuthor($user->getUdala());
            $pdf->SetTitle($user->getUdala() . "-Zerga Ordenantzak");

            $pdf->setFontSubsetting(true);
            $pdf->SetFont('helvetica', '', 11, '', true);

            $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));

            $pdf->AddPage();

            $eguna = date("Y-m-d_His");
            $filename = $this->getFilename($user->getUdala()->getKodea(), "ZergaOrdenantzak-" . $eguna);

            $azala = $this->render('ordenantza/azala.html.twig', array('eguna' => date("Y"), 'udala' => $user->getUdala()));

            $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $azala->getContent(), $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

            $pdf->AddPage();

            foreach ($ordenantzas as $ordenantza)
            {
                $mihtml = $this->render('ordenantza/pdf.html.twig', array('ordenantza' => $ordenantza));
                $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $mihtml->getContent(), $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                $pdf->AddPage();
            }


            $pdf->Output($filename . ".pdf", 'F'); // This will output the PDF as a response directly

            $historikoa->setFitxategia("ZergaOrdenantzak-" . $eguna . ".pdf");
            $this->em->persist($historikoa);
            $this->em->flush();

            return $this->redirectToRoute('admin_historikoa_index');
        }

        return $this->render('historikoa/new.html.twig', array(
            'historikoa' => $historikoa,
            'form'       => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Historikoa entity.
     */
    #[Route(path: '/{id}', name: 'admin_historikoa_show', methods: ['GET'])]
    public function show(Historikoa $historikoa): Response
    {
        $deleteForm = $this->createDeleteForm($historikoa);

        return $this->render('historikoa/show.html.twig', array(
            'historikoa'  => $historikoa,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Historikoa entity.
     */
    #[Route(path: '/{id}/edit', name: 'admin_historikoa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Historikoa $historikoa)
    {
        $deleteForm = $this->createDeleteForm($historikoa);
        $editForm = $this->createForm('App\Form\HistorikoaType', $historikoa);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid())
        {
            
            $this->em->persist($historikoa);
            $this->em->flush();

            return $this->redirectToRoute('admin_historikoa_edit', array('id' => $historikoa->getId()));
        }

        return $this->render('historikoa/edit.html.twig', array(
            'historikoa'  => $historikoa,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Historikoa entity.
     */
    #[Route(path: '/{id}', name: 'admin_historikoa_delete', methods: ['POST','DELETE'])]
    public function delete(Request $request, Historikoa $historikoa): RedirectResponse
    {
        $form = $this->createDeleteForm($historikoa);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->remove($historikoa);
            $this->em->flush();
        }

        return $this->redirectToRoute('admin_historikoa_index');
    }

    /**
     * Creates a form to delete a Historikoa entity.
     *
     * @param Historikoa $historikoa The Historikoa entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Historikoa $historikoa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_historikoa_delete', ['id' => $historikoa->getId()]))
            ->setMethod(Request::METHOD_DELETE)
            ->getForm();
    }

    private function getFilename($udala, $ordenantzaKodea)
    {
        $base = $this->getBaseDir();
        $fs = new Filesystem();
        try
        {
            if ($fs->exists($base . $udala) == false)
            {
                $fs->mkdir($base . $udala);
            }
        } catch (IOExceptionInterface $e)
        {
            echo "Arazoa bat egon da karpeta sortzerakoan " . $e->getPath();
        }

        return $base . $udala . "/" . $ordenantzaKodea;

    }

    private function getBaseDir() {
        $project_root_dir = $this->getParameter('kernel.project_dir');
        $base = $project_root_dir;
        $fs = new Filesystem();
        if ( $fs->exists($project_root_dir.'/public') ) {
            $base = $project_root_dir . '/public/doc/';
        } elseif ( $fs->exists($project_root_dir.'/web') )  {
            $base = $project_root_dir . '/web/doc/';
        } else {
            throw new \Exception('document root directory could not be determined. No "public" o "web" directory found on project');
        }
        return $base;
    }
}
