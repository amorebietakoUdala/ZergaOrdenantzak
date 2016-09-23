<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Ordenantza;
use Symfony\Component\Filesystem\Filesystem;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

class DefaultController extends Controller
{
    /**
     * @Route("/{udala}/{_locale}/", name="frontend_ordenantza_index",
     *         requirements={
     *           "_locale": "eu|es",
     *           "udala": "\d+"
     *     }
     * )
     */
    public function indexAction($udala)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('
          SELECT o FROM AppBundle:Ordenantza o LEFT JOIN AppBundle:Udala u  WITH o.udala=u.id
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
     * @Route("/{udala}/{_locale}/odt", name="frontend_ordenantza_odt",
     *         requirements={
     *           "_locale": "eu|es",
     *           "udala": "\d+"
     *          }
     * )
     * @Method("GET")
     */
    public function odtAction($udala)
    {

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('
          SELECT o FROM AppBundle:Ordenantza o LEFT JOIN AppBundle:Udala u  WITH o.udala=u.id
            WHERE u.kodea = :udala
            ORDER BY o.kodea ASC
        ');
        $query->setParameter('udala', $udala);
        $ordenantzas = $query->getResult();

        $azala = $this->render('frontend/azala.html.twig',array('eguna'=>date("Y"),'udala'=>$this->getUser()->getUdala()));
//        $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $TBS = $this->container->get('opentbs');
        $o = $this->get('kernel')->getRootDir() . '/../web/doc/txantiloia.odt';
//        $TBS->LoadTemplate('doc/txantiloia.odt');
        $TBS->LoadTemplate($o, OPENTBS_ALREADY_UTF8);
        // replace variables
//        $TBS->MergeField('client', array('portada' => $azala));
//        $TBS->MergeField('client', array('portada' => 'KK','name' => 'Ford Prefect'));
        // send the file
//        $TBS->Show(OPENTBS_DOWNLOAD, $o);

//        $TBS->Show(OPENTBS_FILE, $o2);
        $TBS->Show(OPENTBS_DOWNLOAD, '/doc/txantiloia.odt');


        foreach ($ordenantzas as $ordenantza)
        {
            $mihtml = $this->render('frontend/pdf.html.twig', array('ordenantza' => $ordenantza));
//            $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $mihtml->getContent(), $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
//            $pdf->AddPage();
        }
        $filename = $udala."-".date("Y")."ko Zerga Ordenantzak";

        $pdf->Output($filename.".pdf",'I'); // This will output the PDF as a response directly
    }



    /**
     * Finds and displays a Ordenantza entity (PDF).
     *
     * @Route("/{udala}/{_locale}/pdf", name="frontend_ordenantza_pdf",
     *         requirements={
     *           "_locale": "eu|es",
     *           "udala": "\d+"
     *          }
     * )
     * @Method("GET")
     */
    public function pdfAction($udala)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('
          SELECT o FROM AppBundle:Ordenantza o LEFT JOIN AppBundle:Udala u  WITH o.udala=u.id
            WHERE u.kodea = :udala
            ORDER BY o.kodea ASC
        ');
        $query->setParameter('udala', $udala);
        $ordenantzas = $query->getResult();

        $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor($udala);
        $pdf->SetTitle(date("Y")."-Zerga Ordenantzak");

        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 11, '', true);

        $pdf->setHeaderData('',0,'','',array(0,0,0), array(255,255,255) );

        $pdf->AddPage();

        $azala = $this->render('frontend/azala.html.twig',array('eguna'=>date("Y"),'udala'=>$this->getUser()->getUdala()));
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

    private function getFilename($udala, $ordenantzaKodea)
    {
        $fs = new Filesystem();

        $base = $this->get('kernel')->getRootDir() . '/../web/doc/';

        try {
            if ( $fs->exists($base . $udala) == false ) {
                $fs->mkdir($base .$udala);
            }
        } catch (IOExceptionInterface $e) {
            echo "Arazoa bat egon da karpeta sortzerakoan ".$e->getPath();
        }

        return $base.$udala."/".$ordenantzaKodea;

    }



    /**
     * Lists all Historikoa entities.
     *
     * @Route("/{udala}/{_locale}/hist", defaults={"page" = 1}, name="frontend_historikoa_index",
     *         requirements={
     *           "_locale": "eu|es",
     *           "udala": "\d+"
     *          }
     * )
     * @Route("/{udala}/{_locale}/hist/page{page}", name="frontend_historikoa_paginated")
     * @Method("GET")
     */
    public function historikoaAction($page,$udala)
    {
        $em = $this->getDoctrine()->getManager();
        $historikoas =  $em->createQuery("SELECT h FROM AppBundle:Historikoa h order by h.id DESC")->getResult();

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
     * @Route("/{udala}/{_locale}/{id}", name="frontend_ordenantza_show",
     *         requirements={
     *           "_locale": "eu|es",
     *           "udala": "\d+"
     *          }
     * )
     * @Method("GET")
     */
    public function showAction(Ordenantza $ordenantza,$udala)
    {
        return $this->render('frontend/show.html.twig', array(
            'ordenantza' => $ordenantza,
            'udala'=>$udala
        ));
    }





}
