<?php
/**
 * User: iibarguren
 * Date: 31/05/16
 * Time: 10:09
 */

namespace App\Controller;

use App\Entity\Atala;
use App\Entity\Azpiatala;
use App\Entity\Ordenantza;
use App\Entity\Udala;
use App\Repository\AtalaRepository;
use App\Repository\AzpiatalaRepository;
use App\Repository\KontzeptuaRepository;
use App\Repository\OrdenantzaRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * API.
 *
 * @Route("/api")
 */
class ApiController extends AbstractFOSRestController
{

    private $em = null;
    private $ordenantzaRepo = null;
    private $atalaRepo = null;
    private $azpiatalaRepo = null;
    private $kontzeptuaRepo = null;

    public function __construct(
        EntityManagerInterface $em, 
        OrdenantzaRepository $ordenantzaRepo, 
        AtalaRepository $atalaRepo,
        AzpiatalaRepository $azpiatalaRepo,
        KontzeptuaRepository $kontzeptuaRepo
    )
    {
        $this->em = $em;
        $this->ordenantzaRepo = $ordenantzaRepo;
        $this->atalaRepo = $atalaRepo;
        $this->azpiatalaRepo = $azpiatalaRepo;
        $this->kontzeptuaRepo = $kontzeptuaRepo;
    }

//    ORDENANTZAK

    /**
     * Udal baten Ordenantza zerrenda Udal-Kodea bidez.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Ordenantza guztien zerrenda eskuratu",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @return array|View
     * @Annotations\View()
     * @Get("/ordenantzakbykodea/{kodea}.{_format}")
     */
    public function getOrdenantzakbykodea(Request $request, $kodea)
    {
        $_format = $request->get('_format');
        $ordenantzak = $this->ordenantzaRepo->getOrdenantzakByUdalKodea($kodea);
        return $this->returnResponseDataAsFormat($ordenantzak, $_format);
    }

    /**
     * Udal baten Ordenantza zerrenda.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Ordenantza guztien zerrenda eskuratu",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @return array|View
     *
     * @Annotations\View()
     * @Get("/ordenantzakbyid/{udalaid}")
     */
    public function getOrdenantzakByUdala(Request $request, $udalaid)
    {
        $_format = $request->get('_format','json');
        $ordenantzak = $this->em->getRepository(Ordenantza::class)->findBy(['udala' => $udalaid]);
        $udala = $this->em->getRepository(Udala::class)->find($udalaid);
        return $this->returnResponseDataAsFormat($ordenantzak,$_format,'api/index.html.twig',[
            'ordenantzas' => $ordenantzak,
            'udala' => $udala
        ]);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Ordenantza baten informazioa eskuratu"
     * )
     *
     * @Annotations\View()
     * @Get("/ordenantza/{id}")
     * 
     */
    public function getOrdenantza(Request $request, $id)
    {
        $_format = $request->get('_format');
        $ordenantza = $this->em->getRepository(Ordenantza::class)->find($id);
        return $this->returnResponseDataAsFormat($ordenantza, $_format, 'api/ordenantza.html.twig', [
            'ordenantza' => $ordenantza
        ]);
    }

//    ATALAK

    /**
     * Ordenantza batem tributu guztien zerrenda.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Ordenantza baten tributu guztien zerrenda eskuratu",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @param $ordenantzaid
     *
     * @return View
     * @Annotations\View()
     * @Get("/tributuak/{ordenantzaid}")
     */
    public function getAtalak(Request $request, $ordenantzaid)
    {
        $_format = $request->get('_format','json');
        $atalak = $this->atalaRepo->getAtalakByOrdenantzaId($ordenantzaid);
        // TODO Ez funtzionatzen ez duelako plantillarik html formatuan ateratzeko
        return $this->returnResponseDataAsFormat($atalak, $_format);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Tributu baten informazioa eskuratu"
     * )
     *
     * @Annotations\View()
     * @Get("/tributua/{id}")
     */
    public function getAtala(Request $request, $id)
    {
        $_format = $request->get('_format','json');   
        $atala = $this->em->getRepository(Atala::class)->find($id);
        // TODO Ez funtzionatzen ez duelako plantillarik html formatuan ateratzeko
        return $this->returnResponseDataAsFormat($atala, $_format);
    }

//    AZPIATALAK

    /**
     * Udal baten zergen zerrenda.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Udal baten zerga guztien zerrenda eskuratu",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @return View
     *
     * @Annotations\View()
     * @Get("/udalzergak/{udalaid}")
     */
    public function getAzpiatalakByUdala(Request $request, $udalaid)
    {
        $_format = $request->get('_format','json');
        $azpiatalak = $this->azpiatalaRepo->getAzpiatalakByUdala($udalaid);
        return $this->returnResponseDataAsFormat($azpiatalak, $_format);
    }
    
    /**
     * Udal baten zergen zerrenda.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Udal baten zerga guztien zerrenda eskuratu",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @param $tributuaid
     *
     * @return View
     * @Annotations\View()
     * @Get("/zergak/{tributuaid}")
     */
    public function getAzpiatalak(Request $request, $tributuaid)
    {
        $_format = $request->get('_format','json');
        $azpiatalak = $this->azpiatalaRepo->getAzpiatalaByAtala($tributuaid);
        return $this->returnResponseDataAsFormat($azpiatalak, $_format);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Zerga baten informazioa eskuratu",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @return View
     *
     * @Annotations\View()
     * @Get("/zerga/{id}")
     */
    public function getAzpiatala(Request $request, $id)
    {
        $_format = $request->get('_format','json');
        $azpiatala = $this->em->getRepository(Azpiatala::class)->find($id);
        return $this->returnResponseDataAsFormat($azpiatala, $_format);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "kontzeptu baten zenbatekoa itzuli. Indarrean daudenak.",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @return View
     *
     * @Annotations\View()
     * @Get("/kontzeptua/{id}.{_format}")
     */
    public function getKontzeptua($id, $_format = "json")
    {
        /** @var Kontzeptua $kontzeptua */
        $kontzeptua = $this->kontzeptuaRepo->find($id);
        return $this->returnResponseDataAsFormat(str_replace(',', '.', $kontzeptua->getKopuruaProd()), $_format);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Azterketa kategoria baten azterketan parte hartzeko tasa itzuli.",
     *   statusCodes = {
     *     200 = "Zuzena denean"
     *   }
     * )
     *
     * @return View
     *
     * @Annotations\View()
     * @Get("/exam/{kodea}.{_format}")
     */
    public function getExamPrices($kodea, $_format = "json")
    {
        /* 'Tasas según grupo azpiatalaren kodea azterketen prezioak bilatzeko
         * Gero erreziboen aplikazioan helbidea ezartzen da kontzeptu bakoitzeko
         * eta behar den zenbatekoa itzultzen du. Zenbatekoa baino ez du itzultzen.
         */
        $azterketaAzpiatala = $this->getParameter('azterketa_azpiatala');
        $kontzeptua = $this->kontzeptuaRepo->findOneBy([
            'azpiatala' => $azterketaAzpiatala,
            'kodea_prod' => $kodea,
        ]);
        return $this->returnResponseDataAsFormat(str_replace(',', '.', $kontzeptua->getKopuruaProd()), $_format);
    }


    private function returnResponseDataAsFormat($data, $_format = 'json', $template = null, $templateData = []) {
        $view = View::create();
        $view->setData($data);
        //dump($_format);die;
        if (null !== $_format && $_format === 'html') {
            $view->setTemplate($template);
            $view->setTemplateData($templateData);
            $view->setFormat('html');
        }
        if (null !== $_format && $_format === 'json') {
            $view->setFormat('json'); 
            $view->setHeaders([
                'content-type' => 'application/json; charset=utf-8',
                'access-control-allow-origin' => '*',
            ]);
        }
        return $view;
    }    
}