<?php

namespace App\Controller;

use App\Entity\Azpiatala;
use App\Entity\Azpiatalaparrafoa;
use App\Entity\Azpiatalaparrafoaondoren;
use App\Entity\Historikoa;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ordenantza;
use App\Form\OrdenantzaType;
use App\Repository\AtalaparrafoaRepository;
use App\Repository\AtalaRepository;
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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Qipsius\TCPDFBundle\Controller\TCPDFController;

    /**
     * Ordenantza controller.
     */
    #[Route(path: '/{_locale}/admin/ordenantza')]
    class OrdenantzaController extends AbstractController
    {

        public function __construct(
            private readonly EntityManagerInterface $em, 
            private readonly OrdenantzaRepository $ordenantzaRepo, 
            private readonly OrdenantzaparrafoaRepository $ordenantzaparrafoaRepo, 
            private readonly AzpiatalaRepository $azpiatalaRepo, 
            private readonly AtalaparrafoaRepository $atalaparrafoaRepo, 
            private readonly AzpiatalaparrafoaondorenRepository $apoRepo, 
            private readonly AzpiatalaparrafoaRepository $azpiatalaparrafoaRepo, 
            private readonly KontzeptuaRepository $kontzeptuaRepo, 
            private readonly TCPDFController $tcpdfController, 
            private readonly AtalaRepository $atalaRepo,
            private readonly string $rootDir, 
            private readonly string $odtPath
        )
        {
        }

        #[Route(path: '/eguneratu/{id}', name: 'admin_ordenantza_eguneratu', methods: ['POST'])]
        public function eguneratu ( Request $request, $id ): JsonResponse
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
                ['resul' => "OK"]
            );

            return $response;
        }

        #[Route(path: '/eguneratuparrafoa/{id}', name: 'admin_ordenantza_parrafoak_eguneratu', methods: ['POST'])]
        public function eguneratuparrafoak ( Request $request, $id ): JsonResponse
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
                ['resul' => "OK"]
            );

            return $response;
        }

        #[Route(path: '/eguneratuatala/{id}', name: 'admin_ordenantza_atala_eguneratu', methods: ['POST'])]
        public function eguneratuatala ( Request $request, $id ): JsonResponse
        {
            $atala = $this->atalaRepo->find( $id );
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
                ['resul' => "OK"]
            );

            return $response;
        }

        #[Route(path: '/eguneratuatalaparrafoa/{id}', name: 'admin_ordenantza_atalaparrafoa_eguneratu', methods: ['POST'])]
        public function eguneratuatalaparrafoa ( Request $request, $id ): JsonResponse
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
                ['resul' => "OK"]
            );

            return $response;
        }

        #[Route(path: '/eguneratuazpiatala/{id}', name: 'admin_ordenantza_azpiatala_eguneratu', methods: ['POST'])]
        public function eguneratuazpiatala ( Request $request, $id ): JsonResponse
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
                ['resul' => "OK"]
            );

            return $response;
        }

        #[Route(path: '/eguneratuazpiatalaparrafoa/{id}', name: 'admin_ordenantza_azpiatalaparrafoaondoren_eguneratu', methods: ['POST'])]
        public function eguneratuazpiatalaparrafoaondoren ( Request $request, $id ): JsonResponse
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
                ['resul' => "OK"]
            );

            return $response;
        }

        #[Route(path: '/eguneratuazpiatalaparrafoaondoren/{id}', name: 'admin_ordenantza_azpiatalaparrafoa_eguneratu', methods: ['POST'])]
        public function eguneratuazpiatalaparrafoa ( Request $request, $id ): JsonResponse
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
                ['resul' => "OK"]
            );

            return $response;
        }


        #[Route(path: '/eguneratuazpiatalakontzeptuoa/{id}', name: 'admin_ordenantza_azpiatalakontzeptua_eguneratu', methods: ['POST'])]
        public function eguneratuazpiatalakontzeptua ( Request $request, $id ): JsonResponse
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
                ['resul' => "OK"]
            );

            return $response;
        }

        /**
         * Lists all Ordenantza entities.
         */
        #[Route(path: '/', name: 'admin_ordenantza_index', methods: ['GET'])]
        public function index (): Response
        {
            $ordenantzas = $this->ordenantzaRepo->findBy([],['kodea' => 'ASC']);
            return $this->render(
                'ordenantza/index.html.twig',
                [
                    'ordenantzas' => $ordenantzas,
]
            );
        }

        /**
         * Creates a new Ordenantza entity.
         */
        #[Route(path: '/new', name: 'admin_ordenantza_new', methods: ['GET', 'POST'])]
        public function new ( Request $request )
        {
            $ordenantza = new Ordenantza();
            $form = $this->createForm( OrdenantzaType::class, $ordenantza );
            /** @var User $user */
            $user = $this->getUser();
            $form->getData()->setUdala( $user->getUdala() );
            $form->handleRequest( $request );

            if ( $form->isSubmitted() && $form->isValid() ) {
                    $this->em->persist( $ordenantza );
                $this->em->flush();

                return $this->redirectToRoute( 'admin_ordenantza_show', ['id' => $ordenantza->getId()] );
            }

            return $this->render(
                'ordenantza/new.html.twig',
                [
                    'ordenantza' => $ordenantza,
                    'form'       => $form->createView(),
                ]
            );
        }

        /**
         * Finds and displays a Ordenantza entity.
         *
         * @param Ordenantza $ordenantza
         * @return Response
         */
        #[Route(path: '/{id}/erakutsi', name: 'admin_ordenantza_show', methods: ['GET'])]
        public function show ( Ordenantza $ordenantza ): Response
        {
            $deleteForm = $this->createDeleteForm( $ordenantza );
            $deleteForms = [];
            foreach ( $ordenantza->getParrafoak() as $p ) {
                $deleteForms[ $p->getId() ] = $this->createFormBuilder()
                    ->setAction(
                        $this->generateUrl( 'admin_ordenantzaparrafoa_delete', ['id' => $p->getId()] )
                    )
                    ->setMethod( Request::METHOD_DELETE )
                    ->getForm()->createView();
            }

            return $this->render(
                'ordenantza/show.html.twig',
                [
                    'ordenantza'  => $ordenantza,
                    'delete_form' => $deleteForm->createView(),
                ]
            );
        }

        /**
         * Finds and displays a Ordenantza entity.
         */
        #[Route(path: '/pdf/show/{id}', name: 'admin_ordenantza_show_pdf', methods: ['GET'])]
        public function showpdf ( Ordenantza $ordenantza )
        {

            $mihtml = $this->render( 'ordenantza/pdf.html.twig', ['ordenantza' => $ordenantza] );


            $pdf = $this->tcpdfController->create(
                'vertical',
                PDF_UNIT,
                PDF_PAGE_FORMAT,
                true,
                'UTF-8',
                false
            );
            /** @var User $user */
            $user = $this->getUser();
            $pdf->SetAuthor( $user->getUdala() );
            $pdf->SetTitle( ($ordenantza->getIzenburuaeu()) );
            $pdf->setFontSubsetting( true );
            $pdf->SetFont( 'helvetica', '', 11, '', true );

            $pdf->AddPage();
            $path = $this->rootDir.'/../web/doc/';
            $filename = $this->getFilename( $user->getUdala()->getKodea(), $ordenantza->getKodea() );
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
         */
        #[Route(path: '/pdf/export/', name: 'admin_ordenantza_export_pdf', methods: ['GET'])]
        public function exportpdf (): RedirectResponse
        {
            $ordenantzas = $this->ordenantzaRepo->findAll();

            $pdf = $this->tcpdfController->create(
                'vertical',
                PDF_UNIT,
                PDF_PAGE_FORMAT,
                true,
                'UTF-8',
                false
            );
            /** @var User $user */
            $user = $this->getUser();
            $pdf->SetAuthor( $user->getUdala() );
            $pdf->SetTitle( $user->getUdala()."-Zerga Ordenantzak" );

            $pdf->setFontSubsetting( true );
            $pdf->SetFont( 'helvetica', '', 11, '', true );

            $pdf->setHeaderData( '', 0, '', '', [0, 0, 0], [255, 255, 255] );

            $pdf->AddPage();

            $eguna = date( "Y-m-d_His" );
            $filename = $this->getFilename( $user->getUdala()->getKodea(), "ZergaOrdenantzak-".$eguna );
            $azala = $this->render(
                'ordenantza/azala.html.twig',
                ['eguna' => date( "Y" ), 'udala' => $user->getUdala()]
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
                $mihtml = $this->render( 'ordenantza/pdf.html.twig', ['ordenantza' => $ordenantza] );
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
            $historikoa->setUdala( $user->getUdala() );
            $historikoa->setFitxategia( "ZergaOrdenantzak-".$eguna.".pdf" );

            $this->em->persist( $historikoa );
            $this->em->flush();

            return $this->redirectToRoute(
                'admin_historikoa_edit',
                ['id' => $historikoa->getId()]
            );
        }

        /**
         * Displays a form to edit an existing Ordenantza entity.
         */
        #[Route(path: '/{id}/edit', name: 'admin_ordenantza_edit', methods: ['GET', 'POST'])]
        public function edit ( Request $request, Ordenantza $ordenantza ): Response
        {
            $deleteForm = $this->createDeleteForm( $ordenantza );
            $deleteForms = [];

            foreach ( $ordenantza->getParrafoak() as $p ) {
                $deleteForms[ $p->getId() ] = $this->createFormBuilder()
                    ->setAction(
                        $this->generateUrl( 'admin_ordenantzaparrafoa_delete', ['id' => $p->getId()] )
                    )
                    ->setMethod( Request::METHOD_DELETE )
                    ->getForm()->createView();
            }

            return $this->render(
                'ordenantza/edit.html.twig',
                [
                    'ordenantza'  => $ordenantza,
                    'delete_form' => $deleteForm->createView(),
                ]
            );
        }

        /**
         * Publishes a Ordenantza entity.
         */
        #[Route(path: '/{id}/publish', name: 'admin_ordenantza_publish', methods: ['GET', 'POST'])]
        public function publish ( Request $request, Ordenantza $ordenantza ): Response
        {
            $ordenantza->setPublikoa( true );
            $this->em->persist( $ordenantza );
            $this->em->flush();
            
            $referer = $request->headers->get('referer');
            return new RedirectResponse($referer);
        }

        /**
         * UnPublishes a Ordenantza entity.
         */
        #[Route(path: '/{id}/unpublish', name: 'admin_ordenantza_unpublish', methods: ['GET', 'POST'])]
        public function unpublish ( Request $request, Ordenantza $ordenantza ): Response
        {
            $ordenantza->setPublikoa( false );
            $this->em->persist( $ordenantza );
            $this->em->flush();

            $referer = $request->headers->get('referer');
            return new RedirectResponse($referer);
        }

        /**
         * Deletes a Ordenantza entity.
         */
        #[Route(path: '/{id}', name: 'admin_ordenantza_delete', methods: ['DELETE'])]
        public function delete ( Request $request, Ordenantza $ordenantza ): RedirectResponse
        {
            $form = $this->createDeleteForm( $ordenantza );
            $form->handleRequest( $request );

            if ( $form->isSubmitted() && $form->isValid() ) {
                    $this->em->remove( $ordenantza );
                $this->em->flush();
            } else {

                $string = (string)$form->getErrors( true, false );
            }

            return $this->redirectToRoute( 'admin_ordenantza_index' );
        }

        /**
         * Creates a form to delete a Ordenantza entity.
         *
         * @param Ordenantza $ordenantza The Ordenantza entity
         *
         * @return Form The form
         */
        private function createDeleteForm ( Ordenantza $ordenantza )
        {
            return $this->createFormBuilder()
                ->setAction( $this->generateUrl( 'admin_ordenantza_delete', ['id' => $ordenantza->getId()] ) )
                ->setMethod( Request::METHOD_DELETE )
                ->getForm();
        }

        private function getFilename ( $udala, $ordenantzaKodea )
        {
            $fs = new Filesystem();

            $base = $this->rootDir.'/../web/doc/';

            try {
                if ( $fs->exists( $base.$udala ) == false ) {
                    $fs->mkdir( $udala, 0755 );
                }
            } catch ( IOExceptionInterface $e ) {
                echo "Arazoa bat egon da karpeta sortzerakoan ".$e->getPath();
            }

            return $base.$udala."/".$ordenantzaKodea;

        }

        #[Route(path: '/html', name: 'admin_ordenantza_html', methods: ['GET'])]
        public function html (): Response
        {
            $ordenantzas = $this->ordenantzaRepo->findAllOrderByKodea();
            /** @var User $user */
            $user = $this->getUser();
            $nireordenantza = $this->render(
                'ordenantza/web.html.twig',
                [
                    'ordenantzas' => $ordenantzas,
                    'eguna'       => date( "Y" ),
                    'udala'       => $user->getUdala(),
                ]
            );

            $filename = $this->odtPath."/export_".date( "Y_m_d_His" ).".odt";

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

        #[Route(path: '/esportatu/{id}', name: 'admin_ordenantza_esportatu', methods: ['GET'])]
        public function esportatu ( $id ): Response
        {
            $ordenantza = $this->ordenantzaRepo->find( $id );

            $nireordenantza = $this->render(
                'ordenantza/esportatu.html.twig',
                [
                    'ordenantza' => $ordenantza,
                ]
            );

            $filename = $this->odtPath."/export_".date( "Y_m_d_His" ).".odt";

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

        #[Route(path: '/ezabatu/{id}', options: ['expose' => true], name: 'admin_ordenantza_ezabatu', methods: ['GET'])]
        public function ezabatu ( Ordenantza $ordenantza ): Response
        {

            $deleteForm = $this->createDeleteForm( $ordenantza );

            return $this->render(
                'ordenantza/_ordenantza_delete_form.html.twig',
                [
                    'delete_form' => $deleteForm->createView(),
                    'id'          => $ordenantza->getId(),
                ]
            );
        }

    }
