<?php

namespace App\Controller;


use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ordenantza;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function __construct(
        private readonly string $zzoo_aplikazioaren_API_url,
        private readonly string $defaultUdalKodea,
    )
    {
    }

    #[Route(path: '/', name: 'app_home')]
    public function home() {
        return $this->redirectToRoute('frontend_ordenantza_index', [
            'udala' => $this->defaultUdalKodea,
        ]);
    }

    #[Route(path: '/ordenantzak/{udala}/{_locale}/', name: 'ordenantzakList', requirements: ['_locale' => 'eu|es'])]
    public function ordenantzakList($udala): Response
    {
        return $this->render('default\list.html.twig', ['udala' => $udala, 'apiUrl' => $this->zzoo_aplikazioaren_API_url]);
    }

    
    #[Route(path: '/kudeatu', name: 'api_kudeatzailea', methods: ['GET'])]
    public function apikudeatzailea(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('apikudeatzailea.html.twig', [
            'udala' => $user->getUdala()
        ]);
    }

    /**
     * Finds and displays a Ordenantza entity.
     */
    #[Route(path: '/admin/exportatu/{id}', name: 'exportatu', methods: ['GET'])]
    public function exportatu(Ordenantza $ordenantza): Response
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $section = $phpWord->addSection();

        $filename = "zzoo";

        $table1 = $section->addTable(['cellMargin' => 0, 'cellMarginRight' => 0, 'cellMarginBottom' => 0, 'cellMarginLeft' => 0]);

        $table1->addRow(3750);
        $cell1 = $table1->addCell(null, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff']);
        $cell1->addText($ordenantza->getKodea() . " " . $ordenantza->getIzenburuaeu());

        $cell2 = $table1->addCell(null, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff']);
        $cell2->addText($ordenantza->getKodea() . " " . $ordenantza->getIzenburuaes());

        foreach ($ordenantza->getParrafoak() as $parrafoa) {
            $table1->addRow(3750);
            $cell1 = $table1->addCell(null, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff']);
            $cell1->addText($parrafoa->getTestuaeu());

            $cell2 = $table1->addCell(null, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff']);
            $cell2->addText($parrafoa->getTestuaes());
        }

        foreach ($ordenantza->getAtalak() as $atala) {
            $table1->addRow(3750);
            $cell1 = $table1->addCell(null, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff']);
            $cell1->addText($atala->getKodea() . " " . $atala->getIzenburuaeu());

            $cell2 = $table1->addCell(null, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff']);
            $cell2->addText($atala->getKodea() . " " . $atala->getIzenburuaes());

            foreach ($atala->getParrafoak() as $atalaparrafoa) {
                $table1->addRow(3750);
                $cell1 = $table1->addCell(null, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff']);
                $cell1->addText($atalaparrafoa->getTestuaeu());

                $cell2 = $table1->addCell(null, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff']);
                $cell2->addText($atalaparrafoa->getTestuaes());
            }

            foreach ( $atala->getAzpiatalak() as $azpiatala  ) {
                $table1->addRow(3750);
                $cell1 = $table1->addCell(null, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff']);
                $cell1->addText($azpiatala->getKodea() . " " . $azpiatala->getIzenburuaeu());

                $cell2 = $table1->addCell(null, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff']);
                $cell2->addText($azpiatala->getKodea() . " " . $azpiatala->getIzenburuaes());

                foreach ($azpiatala->getParrafoak() as $azpiatalaparrafoa){
                    $table1->addRow(3750);
                    $cell1 = $table1->addCell(null, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff']);
                    $cell1->addText($azpiatalaparrafoa->getTestuaeu());

                    $cell2 = $table1->addCell(null, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff']);
                    $cell2->addText($azpiatalaparrafoa->getTestuaeu());
                }

                foreach ($azpiatala->getKontzeptuak() as $kontzeptua) {
                    $table1->addRow(3750);
                    $cell1 = $table1->addCell(null, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff']);
                    $cell1->addText($kontzeptua->getKontzeptuaeu());

                    $cell2 = $table1->addCell(null, ['valign' => 'top', 'borderSize' => 0, 'borderColor' => 'ffffff']);
                    $cell2->addText($kontzeptua->getKontzeptuaes());

                }

            }

        }

        $properties = $phpWord->getDocInfo();
        $properties->setCreator('Pasaiako Udala');
        $properties->setCompany('Pasaiako Udala');
        $properties->setTitle($filename);
        $properties->setDescription();
        $properties->setCategory('Zerga Ordenantzak');
        $properties->setLastModifiedBy('My name');
        $properties->setKeywords('zerga ordenantzak');

        // Saving the document as ODF file...
        $objWriter = IOFactory::createWriter($phpWord, 'ODText');
        $objWriter->save('doc/' . $filename . '.odt');

        // Saving the document as HTML file...
        $objWriter = IOFactory::createWriter($phpWord, 'HTML');
        $objWriter->save('doc/' . $filename . '.html');

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('doc/' . $filename . '.docx');

        // Saving the document as PDF file...
        $domPdfPath = realpath(__DIR__ . '/../../../vendor/tecnickcom/tcpdf');

        Settings::setPdfRendererPath($domPdfPath);
        Settings::setPdfRendererName('TCPDF');
        $phpWord = IOFactory::load('doc/' . $filename . '.docx');
        $xmlWriter = IOFactory::createWriter($phpWord, 'PDF');
        $xmlWriter->save('doc/' . $filename . '.pdf');

        return new Response("<a href=\"http://zergaordenantzak.dev/doc/helloWorld.odt\">hemen</a>");
    }

    /**
     * Lists all Ordenantza entities.
     *
     */
    #[Route(path: '/hizkuntza/{_locale}', name: 'hizkuntza_aldatu', methods: ['GET'])]
    public function hizkuntza(Request $request): RedirectResponse
    {
        /* historikotik ez du funtzionatzen orrialde batetan badago */
        $ref = str_replace("app_dev.php/", "", parse_url((string) $request->headers->get('referer'),PHP_URL_PATH ));
        $route = $this->container->get('router')->match($ref)['_route'];
        $locale = $request->getLocale();
        $newLocale ="";

        if ($locale == "eu") {
            $request->setLocale('es');
            $newLocale = "es";
        } else {
            $request->setLocale('eu');
            $newLocale = "eu";
        }

        return $this->redirectToRoute($route, ['_locale' => $newLocale]);
        
    }

    function xmlEntities($str)
    {
        $xml = ['&#34;', '&#38;', '&#38;', '&#60;', '&#62;', '&#160;', '&#161;', '&#162;', '&#163;', '&#164;', '&#165;', '&#166;', '&#167;', '&#168;', '&#169;', '&#170;', '&#171;', '&#172;', '&#173;', '&#174;', '&#175;', '&#176;', '&#177;', '&#178;', '&#179;', '&#180;', '&#181;', '&#182;', '&#183;', '&#184;', '&#185;', '&#186;', '&#187;', '&#188;', '&#189;', '&#190;', '&#191;', '&#192;', '&#193;', '&#194;', '&#195;', '&#196;', '&#197;', '&#198;', '&#199;', '&#200;', '&#201;', '&#202;', '&#203;', '&#204;', '&#205;', '&#206;', '&#207;', '&#208;', '&#209;', '&#210;', '&#211;', '&#212;', '&#213;', '&#214;', '&#215;', '&#216;', '&#217;', '&#218;', '&#219;', '&#220;', '&#221;', '&#222;', '&#223;', '&#224;', '&#225;', '&#226;', '&#227;', '&#228;', '&#229;', '&#230;', '&#231;', '&#232;', '&#233;', '&#234;', '&#235;', '&#236;', '&#237;', '&#238;', '&#239;', '&#240;', '&#241;', '&#242;', '&#243;', '&#244;', '&#245;', '&#246;', '&#247;', '&#248;', '&#249;', '&#250;', '&#251;', '&#252;', '&#253;', '&#254;', '&#255;'];
        $html = ['&quot;', '&amp;', '&amp;', '&lt;', '&gt;', '&nbsp;', '&iexcl;', '&cent;', '&pound;', '&curren;', '&yen;', '&brvbar;', '&sect;', '&uml;', '&copy;', '&ordf;', '&laquo;', '&not;', '&shy;', '&reg;', '&macr;', '&deg;', '&plusmn;', '&sup2;', '&sup3;', '&acute;', '&micro;', '&para;', '&middot;', '&cedil;', '&sup1;', '&ordm;', '&raquo;', '&frac14;', '&frac12;', '&frac34;', '&iquest;', '&Agrave;', '&Aacute;', '&Acirc;', '&Atilde;', '&Auml;', '&Aring;', '&AElig;', '&Ccedil;', '&Egrave;', '&Eacute;', '&Ecirc;', '&Euml;', '&Igrave;', '&Iacute;', '&Icirc;', '&Iuml;', '&ETH;', '&Ntilde;', '&Ograve;', '&Oacute;', '&Ocirc;', '&Otilde;', '&Ouml;', '&times;', '&Oslash;', '&Ugrave;', '&Uacute;', '&Ucirc;', '&Uuml;', '&Yacute;', '&THORN;', '&szlig;', '&agrave;', '&aacute;', '&acirc;', '&atilde;', '&auml;', '&aring;', '&aelig;', '&ccedil;', '&egrave;', '&eacute;', '&ecirc;', '&euml;', '&igrave;', '&iacute;', '&icirc;', '&iuml;', '&eth;', '&ntilde;', '&ograve;', '&oacute;', '&ocirc;', '&otilde;', '&ouml;', '&divide;', '&oslash;', '&ugrave;', '&uacute;', '&ucirc;', '&uuml;', '&yacute;', '&thorn;', '&yuml;'];
        $str = str_replace($html, $xml, $str);
        $str = str_ireplace($html, $xml, $str);
        return $str;
    }

}
