<?php

namespace App\Controller;

use App\Entity\Azpiatala;
use App\Entity\Azpiatalaparrafoa;
use App\Entity\Azpiatalaparrafoaondoren;
use App\Entity\Historikoa;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Ordenantza;
use App\Form\OrdenantzaType;
use App\Repository\AtalaparrafoaRepository;
use App\Repository\AzpiatalaparrafoaondorenRepository;
use App\Repository\AzpiatalaparrafoaRepository;
use App\Repository\AzpiatalaRepository;
use App\Repository\KontzeptuaRepository;
use App\Repository\OrdenantzaparrafoaRepository;
use App\Repository\OrdenantzaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\Response;


    /**
     * Ordenantza controller.
     *
     * @Route("/{_locale}/admin/ordenantza")
     */
    class OrdenantzaController extends AbstractController
    {

        private $em;
        private $ordenantzaRepo;
        private $ordenantzaparrafoaRepo;
        private $azpiatalaRepo;
        private $atalaparrafoaRepo;
        private $apoRepo;
        private $azpiatalaparrafoaRepo;
        private $kontzeptuaRepo;

        public function __construct(
            EntityManagerInterface $em, 
            OrdenantzaRepository $ordenantzaRepo, 
            OrdenantzaparrafoaRepository $ordenantzaparrafoaRepo, 
            AzpiatalaRepository $azpiatalaRepo,
            AtalaparrafoaRepository $atalaparrafoaRepo,
            AzpiatalaparrafoaondorenRepository $apoRepo,
            AzpiatalaparrafoaRepository $azpiatalaparrafoaRepo,
            KontzeptuaRepository $kontzeptuaRepo 
        ) 
        {
            $this->em = $em;
            $this->ordenantzaRepo = $ordenantzaRepo;
            $this->ordenantzaparrafoaRepo = $ordenantzaparrafoaRepo;
            $this->azpiatalaRepo = $azpiatalaRepo;
            $this->atalaparrafoaRepo = $atalaparrafoaRepo;
            $this->apoRepo = $apoRepo;
            $this->azpiatalaparrafoaRepo = $azpiatalaparrafoaRepo;
            $this->kontzeptuaRepo = $kontzeptuaRepo;
        }

        /**
         * @Route("/eguneratu/{id}", name="admin_ordenantza_eguneratu")
         * @Method("POST")
         */
        public function eguneratuAction ( Request $request, $id )
        {
            /** @var Ordenantza $ordenantza  */
            $ordenantza = $this->ordenantzaRepo->find( $id );
            $name = $request->request->get( 'name' );
            $value = $request->request->get( 'value' );

            $ezabatu = ["<br>","&lt;br&gt;","<br \/>", "<br\/>", "&nbsp;","&amp;nbsp;","&amp"];
            switch ( $name ) {
                case "izenburuaeu":
                    $testua = str_replace($ezabatu, "", $value);
                    $ordenantza->setIzenburuaeu( $testua );
                    break;
                case "izenburuaes":
                    $testua = str_replace($ezabatu, "", $value);
                    $ordenantza->setIzenburuaes( $testua );
                    break;
                case "kodea":
                    $ordenantza->setKodea( str_replace($ezabatu, "", $value) );
                    break;
            }

            $this->em->persist( $ordenantza );
            $this->em->flush();
            $response = new JsonResponse();
            $response->setData(
                array (
                    'resul' => "OK",
                )
            );

            return $response;
        }

        /**
         * @Route("/eguneratuparrafoa/{id}", name="admin_ordenantza_parrafoak_eguneratu")
         * @Method("POST")
         */
        public function eguneratuparrafoakAction ( Request $request, $id )
        {
            $ordenantzaparrafoa = $this->ordenantzaparrafoaRepo->find( $id );
            $name = $request->request->get( 'name' );
            $value = $request->request->get( 'value' );


            switch ( $name ) {
                case "testuaeu":
                    $ordenantzaparrafoa->setTestuaeu( $value );
                    break;
                case "testuaes":
                    $ordenantzaparrafoa->setTestuaes( $value );
                    break;
                case "ordena":
                    $ordenantzaparrafoa->setOrdena( $value );
            }

            $this->em->persist( $ordenantzaparrafoa );
            $this->em->flush();
            $response = new JsonResponse();
            $response->setData(
                array (
                    'resul' => "OK",
                )
            );

            return $response;
        }

        /**
         * @Route("/eguneratuatala/{id}", name="admin_ordenantza_atala_eguneratu")
         * @Method("POST")
         */
        public function eguneratuatalaAction ( Request $request, $id )
        {
            $atala = $this->azpiatalaRepo->find( $id );
            $name = $request->request->get( 'name' );
            $value = $request->request->get( 'value' );

            $ezabatu = ["<br>","&lt;br&gt;","<br \/>", "<br\/>", "&nbsp;","&amp;nbsp;","&amp"];

            switch ( $name ) {
                case "kodea":
                    $atala->setKodea( $value );
                    break;
                case "izenburuaeu":
                    $testua = str_replace($ezabatu, "", $value);
                    $atala->setIzenburuaeu( $testua );
                    break;
                case "izenburuaes":
                    $testua = str_replace($ezabatu, "", $value);
                    $atala->setIzenburuaes( $testua );
                    break;
            }

            $this->em->persist( $atala );
            $this->em->flush();
            $response = new JsonResponse();
            $response->setData(
                array (
                    'resul' => "OK",
                )
            );

            return $response;
        }

        /**
         * @Route("/eguneratuatalaparrafoa/{id}", name="admin_ordenantza_atalaparrafoa_eguneratu")
         * @Method("POST")
         */
        public function eguneratuatalaparrafoaAction ( Request $request, $id )
        {
            $atalap = $this->atalaparrafoaRepo->find( $id );
            $name = $request->request->get( 'name' );
            $value = $request->request->get( 'value' );


            switch ( $name ) {
                case "testuaeu":
                    $atalap->setTestuaeu( $value );
                    break;
                case "testuaes":
                    $atalap->setTestuaes( $value );
                    break;
                case "ordena":
                    $atalap->setOrdena( $value );
                    break;
            }

            $this->em->persist( $atalap );
            $this->em->flush();
            $response = new JsonResponse();
            $response->setData(
                array (
                    'resul' => "OK",
                )
            );

            return $response;
        }

        /**
         * @Route("/eguneratuazpiatala/{id}", name="admin_ordenantza_azpiatala_eguneratu")
         * @Method("POST")
         */
        public function eguneratuazpiatalaAction ( Request $request, $id )
        {
            /** @var Azpiatala $azpiatala */
            $azpiatala = $this->azpiatalaRepo->find( $id );
            $name = $request->request->get( 'name' );
            $value = $request->request->get( 'value' );

            $ezabatu = ["<br>","&lt;br&gt;","<br \/>", "<br\/>", "&nbsp;","&amp;nbsp;","&amp"];

            switch ( $name ) {
                case "izenburuaeu":
                    $testua = str_replace($ezabatu, "", $value);
                    $azpiatala->setIzenburuaeu( $testua );
                    break;
                case "izenburuaes":
                    $testua = str_replace($ezabatu, "", $value);
                    $azpiatala->setIzenburuaes( $testua );
                    break;
                case "kodea":
                    $azpiatala->setKodea( $value );
                    break;
            }

            $this->em->persist( $azpiatala );
            $this->em->flush();
            $response = new JsonResponse();
            $response->setData(
                array (
                    'resul' => "OK",
                )
            );

            return $response;
        }

        /**
         * @Route("/eguneratuazpiatalaparrafoa/{id}", name="admin_ordenantza_azpiatalaparrafoaondoren_eguneratu")
         * @Method("POST")
         */
        public function eguneratuazpiatalaparrafoaondorenAction ( Request $request, $id )
        {

            /** @var Azpiatalaparrafoaondoren $azpiatalap */
            $azpiatalap = $this->apoRepo->find( $id );
            $name = $request->request->get( 'name' );
            $value = $request->request->get( 'value' );


            switch ( $name ) {
                case "testuaeu":
                    $azpiatalap->setTestuaeu( $value );
                    break;
                case "testuaes":
                    $azpiatalap->setTestuaes( $value );
                    break;
                case "ordena":
                    $azpiatalap->setOrdena( $value );
                    break;
            }

            $this->em->persist( $azpiatalap );
            $this->em->flush();
            $response = new JsonResponse();
            $response->setData(
                array (
                    'resul' => "OK",
                )
            );

            return $response;
        }

        /**
         * @Route("/eguneratuazpiatalaparrafoaondoren/{id}", name="admin_ordenantza_azpiatalaparrafoa_eguneratu")
         * @Method("POST")
         */
        public function eguneratuazpiatalaparrafoaAction ( Request $request, $id )
        {

            /** @var Azpiatalaparrafoa $azpiatalap */
            $azpiatalap = $this->azpiatalaparrafoaRepo->find( $id );
            $name = $request->request->get( 'name' );
            $value = $request->request->get( 'value' );


            switch ( $name ) {
                case "testuaeu":
                    $azpiatalap->setTestuaeu( $value );
                    break;
                case "testuaes":
                    $azpiatalap->setTestuaes( $value );
                    break;
                case "ordena":
                    $azpiatalap->setOrdena( $value );
                    break;
            }

            $this->em->persist( $azpiatalap );
            $this->em->flush();
            $response = new JsonResponse();
            $response->setData(
                array (
                    'resul' => "OK",
                )
            );

            return $response;
        }


        /**
         * @Route("/eguneratuazpiatalakontzeptuoa/{id}", name="admin_ordenantza_azpiatalakontzeptua_eguneratu")
         * @Method("POST")
         */
        public function eguneratuazpiatalakontzeptuaAction ( Request $request, $id )
        {
            $azpiatalap = $this->kontzeptuaRepo->find( $id );
            $name = $request->request->get( 'name' );
            $value = $request->request->get( 'value' );


            switch ( $name ) {
                case "kontzeptuaeu":
                    $azpiatalap->setKontzeptuaeu( $value );
                    break;
                case "kontzeptuaes":
                    $azpiatalap->setKontzeptuaes( $value );
                    break;
                case "kopurua":
                    $azpiatalap->setKopurua( $value );
                    break;
                case "kontzeptuaes":
                    $azpiatalap->setUnitatea( $value );
                    break;

            }

            $this->em->persist( $azpiatalap );
            $this->em->flush();
            $response = new JsonResponse();
            $response->setData(
                array (
                    'resul' => "OK",
                )
            );

            return $response;
        }

        /**
         * Lists all Ordenantza entities.
         *
         * @Route("/", name="admin_ordenantza_index")
         * @Method("GET")
         */
        public function indexAction ()
        {
            $ordenantzas = $this->ordenantzaRepo->findBy( array (), array ( 'kodea' => 'ASC' ) );

            return $this->render(
                'ordenantza/index.html.twig',
                array (
                    'ordenantzas' => $ordenantzas,
                )
            );
        }

        /**
         * Creates a new Ordenantza entity.
         *
         * @Route("/new", name="admin_ordenantza_new")
         * @Method({"GET", "POST"})
         */
        public function newAction ( Request $request )
        {
            $ordenantza = new Ordenantza();
            $form = $this->createForm( OrdenantzaType::class, $ordenantza );
            $form->getData()->setUdala( $this->getUser()->getUdala() );
            $form->handleRequest( $request );

            if ( $form->isSubmitted() && $form->isValid() ) {
                    $this->em->persist( $ordenantza );
                $this->em->flush();

                return $this->redirectToRoute( 'admin_ordenantza_show', array ( 'id' => $ordenantza->getId() ) );
            }

            return $this->render(
                'ordenantza/new.html.twig',
                array (
                    'ordenantza' => $ordenantza,
                    'form'       => $form->createView(),
                )
            );
        }

        /**
         * Finds and displays a Ordenantza entity.
         *
         * @Route("/{id}/erakutsi", name="admin_ordenantza_show")
         * @Method("GET")
         * @param Ordenantza $ordenantza
         *
         * @return Response
         */
        public function showAction ( Ordenantza $ordenantza )
        {
            $deleteForm = $this->createDeleteForm( $ordenantza );
            $deleteForms = array ();
            foreach ( $ordenantza->getParrafoak() as $p ) {
                $deleteForms[ $p->getId() ] = $this->createFormBuilder()
                    ->setAction(
                        $this->generateUrl( 'admin_ordenantzaparrafoa_delete', array ( 'id' => $p->getId() ) )
                    )
                    ->setMethod( 'DELETE' )
                    ->getForm()->createView();
            }

            return $this->render(
                'ordenantza/show.html.twig',
                array (
                    'ordenantza'  => $ordenantza,
                    'delete_form' => $deleteForm->createView(),
                )
            );
        }

        /**
         * Finds and displays a Ordenantza entity.
         *
         * @Route("/pdf/show/{id}", name="admin_ordenantza_show_pdf")
         * @Method("GET")
         */
        public function showpdfAction ( Ordenantza $ordenantza )
        {

            $mihtml = $this->render( 'ordenantza/pdf.html.twig', array ( 'ordenantza' => $ordenantza ) );


            $pdf = $this->get( "white_october.tcpdf" )->create(
                'vertical',
                PDF_UNIT,
                PDF_PAGE_FORMAT,
                true,
                'UTF-8',
                false
            );
            $pdf->SetAuthor( $this->getUser()->getUdala() );
            $pdf->SetTitle( ($ordenantza->getIzenburuaeu()) );
            $pdf->setFontSubsetting( true );
            $pdf->SetFont( 'helvetica', '', 11, '', true );

            $pdf->AddPage();
            $path = $this->get( 'kernel' )->getRootDir().'/../web/doc/';
            $filename = $this->getFilename( $this->getUser()->getUdala()->getKodea(), $ordenantza->getKodea() );
            $pdf->writeHTMLCell(
                $w = 0,
                $h = 0,
                $x = '',
                $y = '',
                $mihtml->getContent(),
                $border = 0,
                $ln = 1,
                $fill = 0,
                $reseth = true,
                $align = '',
                $autopadding = true
            );
            $pdf->Output( $filename.".pdf", 'I' ); // This will output the PDF as a response directly
        }

        /**
         * Finds and displays a Ordenantza entity.
         *
         * @Route("/pdf/export/", name="admin_ordenantza_export_pdf")
         * @Method("GET")
         */
        public function exportpdfAction ()
        {
            $ordenantzas = $this->ordenantzaRepo->findAll();

            $pdf = $this->get( "white_october.tcpdf" )->create(
                'vertical',
                PDF_UNIT,
                PDF_PAGE_FORMAT,
                true,
                'UTF-8',
                false
            );
            $pdf->SetAuthor( $this->getUser()->getUdala() );
            $pdf->SetTitle( $this->getUser()->getUdala()."-Zerga Ordenantzak" );

            $pdf->setFontSubsetting( true );
            $pdf->SetFont( 'helvetica', '', 11, '', true );

            $pdf->setHeaderData( '', 0, '', '', array ( 0, 0, 0 ), array ( 255, 255, 255 ) );

            $pdf->AddPage();

            $eguna = date( "Y-m-d_His" );
            $filename = $this->getFilename( $this->getUser()->getUdala()->getKodea(), "ZergaOrdenantzak-".$eguna );
            $azala = $this->render(
                'ordenantza/azala.html.twig',
                array ( 'eguna' => date( "Y" ), 'udala' => $this->getUser()->getUdala() )
            );
            $pdf->writeHTMLCell(
                $w = 0,
                $h = 0,
                $x = '',
                $y = '',
                $azala->getContent(),
                $border = 0,
                $ln = 1,
                $fill = 0,
                $reseth = true,
                $align = '',
                $autopadding = true
            );
            $pdf->AddPage();

            foreach ( $ordenantzas as $ordenantza ) {
                $mihtml = $this->render( 'ordenantza/pdf.html.twig', array ( 'ordenantza' => $ordenantza ) );
                $pdf->writeHTMLCell(
                    $w = 0,
                    $h = 0,
                    $x = '',
                    $y = '',
                    $mihtml->getContent(),
                    $border = 0,
                    $ln = 1,
                    $fill = 0,
                    $reseth = true,
                    $align = '',
                    $autopadding = true
                );
                $pdf->AddPage();
            }

            $pdf->Output( $filename.".pdf", 'F' ); // This will output the PDF as a response directly

            $historikoa = New Historikoa();
            $historikoa->setCreatedAt( New \DateTime() );
            $historikoa->setUpdatedAt( New \DateTime() );
            $historikoa->setUdala( $this->getUser()->getUdala() );
            $historikoa->setFitxategia( "ZergaOrdenantzak-".$eguna.".pdf" );

            $this->em->persist( $historikoa );
            $this->em->flush();

            return $this->redirectToRoute(
                'admin_historikoa_edit',
                array (
                    'id' => $historikoa->getId(),
                )
            );
        }

        /**
         * Displays a form to edit an existing Ordenantza entity.
         *
         * @Route("/{id}/edit", name="admin_ordenantza_edit")
         * @Method({"GET", "POST"})
         */
        public function editAction ( Request $request, Ordenantza $ordenantza )
        {
            $deleteForm = $this->createDeleteForm( $ordenantza );
            $deleteForms = array ();

            foreach ( $ordenantza->getParrafoak() as $p ) {
                $deleteForms[ $p->getId() ] = $this->createFormBuilder()
                    ->setAction(
                        $this->generateUrl( 'admin_ordenantzaparrafoa_delete', array ( 'id' => $p->getId() ) )
                    )
                    ->setMethod( 'DELETE' )
                    ->getForm()->createView();
            }

            return $this->render(
                'ordenantza/edit.html.twig',
                array (
                    'ordenantza'  => $ordenantza,
                    'delete_form' => $deleteForm->createView(),
                )
            );
        }

        /**
         * Deletes a Ordenantza entity.
         *
         * @Route("/{id}", name="admin_ordenantza_delete")
         * @Method("DELETE")
         */
        public function deleteAction ( Request $request, Ordenantza $ordenantza )
        {
            $form = $this->createDeleteForm( $ordenantza );
            $form->handleRequest( $request );

            if ( $form->isSubmitted() && $form->isValid() ) {
                    $this->em->remove( $ordenantza );
                $this->em->flush();
            } else {

                $string = (string)$form->getErrors( true, false );
                //dump( $form->getErrors( true, false ) );
            }

            return $this->redirectToRoute( 'admin_ordenantza_index' );
        }

        /**
         * Creates a form to delete a Ordenantza entity.
         *
         * @param Ordenantza $ordenantza The Ordenantza entity
         *
         * @return \Symfony\Component\Form\Form The form
         */
        private function createDeleteForm ( Ordenantza $ordenantza )
        {
            return $this->createFormBuilder()
                ->setAction( $this->generateUrl( 'admin_ordenantza_delete', array ( 'id' => $ordenantza->getId() ) ) )
                ->setMethod( 'DELETE' )
                ->getForm();
        }

        private function getFilename ( $udala, $ordenantzaKodea )
        {
            $fs = new Filesystem();

            $base = $this->get( 'kernel' )->getRootDir().'/../web/doc/';

            try {
                if ( $fs->exists( $base.$udala ) == false ) {
                    $fs->mkdir( $udala, 0755 );
                }
            } catch ( IOExceptionInterface $e ) {
                echo "Arazoa bat egon da karpeta sortzerakoan ".$e->getPath();
            }

            return $base.$udala."/".$ordenantzaKodea;

        }

        /**
         * @Route("/html", name="admin_ordenantza_html")
         * @Method("GET")
         */
        public function htmlAction ()
        {
            $ordenantzas = $this->ordenantzaRepo->findAllOrderByKodea();

            $nireordenantza = $this->render(
                'ordenantza/web.html.twig',
                array (
                    'ordenantzas' => $ordenantzas,
                    'eguna'       => date( "Y" ),
                    'udala'       => $this->getUser()->getUdala(),
                )
            );

            $filename = "doc/export_".date( "Y_m_d_His" ).".odt";

            file_put_contents( $filename, $nireordenantza->getContent() );

            // Generate response
            $response = new Response();

            // Set headers
            $response->headers->set( 'Cache-Control', 'private' );
            $response->headers->set( 'Content-type', mime_content_type( $filename ) );
            $response->headers->set( 'Content-Disposition', 'attachment; filename="'.basename( $filename ).'";' );
            $response->headers->set( 'Content-length', filesize( $filename ) );

            // Send headers before outputting anything
            $response->sendHeaders();

            $response->setContent( file_get_contents( $filename ) );

            return $response;

        }

        /**
         * @Route("/esportatu/{id}", name="admin_ordenantza_esportatu")
         * @Method("GET")
         */
        public function esportatuAction ( $id )
        {
            $ordenantza = $this->ordenantzaRepo->find( $id );

            $nireordenantza = $this->render(
                'ordenantza/esportatu.html.twig',
                array (
                    'ordenantza' => $ordenantza,
                )
            );

            $filename = "doc/export_".date( "Y_m_d_His" ).".odt";

            file_put_contents( $filename, $nireordenantza->getContent() );

            // Generate response
            $response = new Response();

            // Set headers
            $response->headers->set( 'Cache-Control', 'private' );
            $response->headers->set( 'Content-type', mime_content_type( $filename ) );
            $response->headers->set( 'Content-Disposition', 'attachment; filename="'.basename( $filename ).'";' );
            $response->headers->set( 'Content-length', filesize( $filename ) );

            // Send headers before outputting anything
            $response->sendHeaders();

            $response->setContent( file_get_contents( $filename ) );

            return $response;

        }

        /**
         * @Route("/ezabatu/{id}", options = { "expose" = true }, name="admin_ordenantza_ezabatu")
         * @Method("GET")
         */
        public function ezabatuAction ( Ordenantza $ordenantza )
        {

            $deleteForm = $this->createDeleteForm( $ordenantza );

            return $this->render(
                'ordenantza/_ordenantza_delete_form.html.twig',
                array (
                    'delete_form' => $deleteForm->createView(),
                    'id'          => $ordenantza->getId(),
                )
            );
        }

    }
