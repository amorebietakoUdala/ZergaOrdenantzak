<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ordenantza;
use App\Entity\User;
use App\Repository\HistorikoaRepository;
use App\Repository\OrdenantzaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\Response;
use Qipsius\TCPDFBundle\Controller\TCPDFController;

class FrontendController extends AbstractController
{

    private $em;
    private $ordenantzaRepo;
    private $historikoaRepo;
    private $tcpdfController;
    private $rootDir;

    public function __construct(
        EntityManagerInterface $em, 
        OrdenantzaRepository $ordenantzaRepo, 
        HistorikoaRepository $historikoaRepo, 
        TCPDFController $tcpdfController,
        string $rootDir )
    {
        $this->em = $em;
        $this->ordenantzaRepo = $ordenantzaRepo;
        $this->historikoaRepo = $historikoaRepo;
        $this->tcpdfController = $tcpdfController;
        $this->rootDir = $rootDir;
    }

    /**
     * @Route("/{udala}/{_locale}/", name="frontend_ordenantza_index",
     *         requirements={
     *           "_locale": "eu|es",
     *           "udala": "\d+"
     *     }
     * )
     */
    public function index(int $udala): Response
    {
        $query = $this->em->createQuery('
          SELECT o FROM App:Ordenantza o LEFT JOIN App:Udala u  WITH o.udala=u.id
            WHERE u.kodea = :udala
            ORDER BY o.kodea ASC
        ');
        $query->setParameter('udala', $udala);
        $ordenantzak = $query->getResult();

        return $this->render('frontend\index.html.twig', array(
            'ordenantzas' => $ordenantzak,
            'udala'=>$udala,
        ));        
        
    }

    /**
     * Finds and displays a Ordenantza entity (OFT).
     *
     * @Route("/{id}/html", name="frontend_ordenantza_html", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function html(int $id): Response
    {
       $ordenantza = $this->ordenantzaRepo->find($id);

        $fitxero=  $this->render('frontend/mihtml.html.twig', array(
            'ordenantza' => $ordenantza
        ));

        $filename = "export_".date("Y_m_d_His").".odt";

        file_put_contents($filename, $fitxero->getContent());

        // Generate response
        $response = new Response();

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($filename));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '";');
        $response->headers->set('Content-length', filesize($filename));

        // Send headers before outputting anything
        $response->sendHeaders();

        $response->setContent(file_get_contents($filename));

        return $response;

    }

    public function f_html2odt($FieldName, &$CurrVal) {
        $CurrVal= str_replace('<br />', '<text:line-break/>', $CurrVal);
    }

    /**
     * Finds and displays a Ordenantza entity (PDF).
     *
     * @Route("/{udala}/{_locale}/pdf", name="frontend_ordenantza_pdf", requirements={"_locale"="eu|es", "udala"="\d+"}, methods={"GET"})
     */
    public function pdf(int $udala, TCPDFController $tcpdfController)
    {
        $ordenantzas = $this->ordenantzaRepo->findBy([ 'udala' => $udala ]);

        $pdf = $tcpdfController->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor($udala);
        $pdf->SetTitle(date("Y")."-Zerga Ordenantzak");

        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 11, '', true);

        $pdf->setHeaderData('',0,'','',array(0,0,0), array(255,255,255) );

        $pdf->AddPage();
        /** @var User $user */
        $user = $this->getUser();
        $azala = $this->render('frontend/azala.html.twig',array('eguna'=>date("Y"),'udala'=>$user->getUdala()));
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $azala->getContent(), $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->AddPage();

        foreach ($ordenantzas as $ordenantza)
        {
            $mihtml = $this->render('frontend/pdf.html.twig', array('ordenantza' => $ordenantza));
            $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $mihtml->getContent(), $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            $pdf->AddPage();
        }
        $filename = $udala."-".date("Y")."ko Zerga Ordenantzak";

        $pdf->Output($filename.".pdf",'I'); // This will output the PDF as a response directly
    }

    /**
     * Lists all Historikoa entities.
     *
     * @Route("/{udala}/{_locale}/hist", defaults={"page"=1}, name="frontend_historikoa_index", requirements={"_locale"="eu|es", "udala"="\d+"}, methods={"GET"})
     * @Route("/{udala}/{_locale}/hist/page{page}", name="frontend_historikoa_paginated", methods={"GET"})
     */
    public function historikoa($page,int $udala): Response
    {
        //$historikoas =  $this->em->createQuery("SELECT h FROM App:Historikoa h order by h.id DESC")->getResult();
        $historikoas = $this->historikoaRepo->findBy([],['id' => 'DESC']);
        $adapter = new ArrayAdapter($historikoas);
        $pagerfanta = new Pagerfanta($adapter);
        try {
            $entities = $pagerfanta
                ->setMaxPerPage(25)
                ->setCurrentPage($page)
                ->getCurrentPageResults()
            ;
        } catch (\Pagerfanta\Exception\NotValidCurrentPageException $e) {
            throw $this->createNotFoundException("Orria ez da existitzen");
        }

        return $this->render('frontend/historikoa.html.twig', array(
            'historikoas' => $entities,
            'pager' => $pagerfanta,
            'udala' => $udala
        ));
    }

    /**
     * Finds and displays a Ordenantza entity.
     *
     * @Route("/{udala}/{_locale}/{id}", name="frontend_ordenantza_show", requirements={"_locale"="eu|es", "udala"="\d+"}, methods={"GET"})
     */
    public function show(Ordenantza $ordenantza,int $udala): Response
    {
        return $this->render('frontend/show.html.twig', array(
            'ordenantza' => $ordenantza,
            'udala'=>$udala
        ));
    }
}
