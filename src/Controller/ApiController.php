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
use App\Repository\UdalaRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\View\View;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    private $udalaRepo = null;

    public function __construct(
        EntityManagerInterface $em, 
        OrdenantzaRepository $ordenantzaRepo, 
        AtalaRepository $atalaRepo,
        AzpiatalaRepository $azpiatalaRepo,
        KontzeptuaRepository $kontzeptuaRepo,
        UdalaRepository $udalaRepo
    )
    {
        $this->em = $em;
        $this->ordenantzaRepo = $ordenantzaRepo;
        $this->atalaRepo = $atalaRepo;
        $this->azpiatalaRepo = $azpiatalaRepo;
        $this->kontzeptuaRepo = $kontzeptuaRepo;
        $this->udalaRepo = $udalaRepo;
    }

//    ORDENANTZAK

    /**
     * Ordenantza guztien zerrenda Udal kodea adierazita
     * 
     * @return array|View
     * 
     * @Annotations\View()
     * @OA\Response(
     *     response=200,
     *     description="Ordenantza guztien zerrenda Udal kodea adierazita",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Ordenantza::class))
     *     )
     * )
     * @OA\Response(
     *    response=404,
     *    description="Udala ez da aurkitu"
     * )     
     * @Get("/ordenantzakbykodea/{kodea}")
     */
    public function getOrdenantzakbykodea(Request $request, $kodea)
    {
        $_format = $request->get('_format','json');
        $udala = $this->udalaRepo->findOneBy(['kodea' => $kodea]);
        if ( null === $udala) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        $ordenantzak = $this->ordenantzaRepo->getOrdenantzakByUdalKodea($kodea);
        return $this->returnResponseDataAsFormat($ordenantzak, $_format);
    }

    /**
     * Udal baten ordenantza zerrenda udalaren identifikatzailea adierazita
     *
     * @return array|View
     *
     * @Annotations\View()
     * @OA\Response(
     *     response=200,
     *     description="Udal baten ordenantza zerrenda udalaren identifikatzailea adierazita",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Ordenantza::class))
     *     )
     * )
     * @OA\Response(
     *    response=404,
     *    description="Udala ez da aurkitu"
     * )     
     * @Get("/ordenantzakbyid/{udalaid}")
     */
    public function getOrdenantzakByUdala(Request $request, $udalaid)
    {
        $_format = $request->get('_format','json');
        $udala = $this->udalaRepo->find($udalaid);
        if ( null === $udala) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        $ordenantzak = $this->em->getRepository(Ordenantza::class)->findBy(['udala' => $udalaid]);
        $udala = $this->em->getRepository(Udala::class)->find($udalaid);
        return $this->returnResponseDataAsFormat($ordenantzak,$_format,'api/index.html.twig',[
            'ordenantzas' => $ordenantzak,
            'udala' => $udala
        ]);
    }

    /**
     * Ordenantza bat itzuli json edo html formatuan
     *
     * @OA\Parameter(
     *    name="_format",
     *    in="query",
     *    description="Formato de respuesta (json o html)",
     *    required=false,
     *    @OA\Schema(type="string", enum={"json", "html"})
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Ordenantza bat itzuli json edo html formatuan",
     *    @OA\JsonContent(ref=@Model(type=Ordenantza::class))
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Ordenantza ez da aurkitu"
     * )
     * @Annotations\View()
     * @Get("/ordenantza/{id}")
     * @return array|View
     */
    public function getOrdenantza(Request $request, $id)
    {
        $_format = $request->get('_format','json');
        $ordenantza = $this->ordenantzaRepo->find($id);
        if ( null === $ordenantza ) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        return $this->returnResponseDataAsFormat($ordenantza, $_format, 'api/ordenantza.html.twig', [
            'ordenantza' => $ordenantza
        ]);
    }

//    ATALAK

    /**
     * Ordenantza baten tributu guztien zerrenda.
     *
     * @param $ordenantzaid
     *
     * @OA\Response(
     *    response=200,
     *    description="Ordenantza baten tributu guztien zerrenda",
     *    @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Atala::class))
     *    )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Ordenantza ez da aurkitu"
     * )
     * 
     * @return View
     * @Annotations\View()
     * @Get("/tributuak/{ordenantzaid}")
     */
    public function getAtalak(Request $request, $ordenantzaid)
    {
        $_format = $request->get('_format','json');
        $ordenantza = $this->ordenantzaRepo->find($ordenantzaid);
        if ( null === $ordenantza ) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }        
        $atalak = $this->atalaRepo->getAtalakByOrdenantzaId($ordenantzaid);
        return $this->returnResponseDataAsFormat($atalak, $_format);
    }

    /**
     * Tributu bat itzuli
     *
     * @param $id
     *
     * @OA\Response(
     *    response=200,
     *    description="Tributu bat itzuli",
     *    @OA\JsonContent(ref=@Model(type=Atala::class))
     *    )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Tributua ez da aurkitu"
     * )
     * 
     * @return View
     * @Annotations\View()
     * @Get("/tributua/{id}")
     */
    public function getAtala(Request $request, $id)
    {
        $_format = $request->get('_format','json');   
        $atala = $this->atalaRepo->find($id);
        if ( null === $atala ) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }        
        return $this->returnResponseDataAsFormat($atala, $_format);
    }

//    AZPIATALAK

    /**
     * Udal baten zergen zerrenda
     *
     * @OA\Response(
     *    response=200,
     *    description="Udal baten zergen zerrenda",
     *    @OA\Items(ref=@Model(type=Azpiatala::class))
     *    )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Udala ez da aurkitu"
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
        $udala = $this->udalaRepo->find($udalaid);
        if ( null === $udala ) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }        
        $azpiatalak = $this->azpiatalaRepo->getAzpiatalakByUdala($udalaid);
        return $this->returnResponseDataAsFormat($azpiatalak, $_format);
    }
    
    /**
     * Udal baten zergen zerrenda tributu identifikatzailea erabiliz
     *
     * @param $tributuaid
     *
     * @OA\Response(
     *    response=200,
     *    description="Udal baten zergen zerrenda tributu identifikatzailea erabiliz",
     *    @OA\Items(ref=@Model(type=Azpiatala::class))
     *    )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Udala ez da aurkitu"
     * )
     *
     * @return View
     * @Annotations\View()
     * @Get("/zergak/{tributuaid}")
     */
    public function getAzpiatalak(Request $request, $tributuaid)
    {
        $_format = $request->get('_format','json');
        $tributua = $this->atalaRepo->find($tributuaid);
        if ( null === $tributua ) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }        
        $azpiatalak = $this->azpiatalaRepo->getAzpiatalaByAtala($tributuaid);
        return $this->returnResponseDataAsFormat($azpiatalak, $_format);
    }

    /**
     * Zerga bat itzuli bere identifikatzailea erabiliz
     * 
     * @OA\Response(
     *    response=200,
     *    description="Zerga bat itzuli bere identifikatzailea erabiliz",
     *    @OA\JsonContent(ref=@Model(type=Azpiatala::class))
     *    )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Udala ez da aurkitu"
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
        $azpiatala = $this->azpiatalaRepo->find($id);
        if ( null === $azpiatala ) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }        
        return $this->returnResponseDataAsFormat($azpiatala, $_format);
    }

    /**
     * Kontzeptu bat itzuli bere identifikatzailea erabiliz
     * 
     * @OA\Response(
     *    response=200,
     *    description="Kontzeptu bat itzuli bere identifikatzailea erabiliz",
     *    @OA\JsonContent(ref=@Model(type=Kontzeptua::class))
     *    )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Ez da kontzeptua aurkitu"
     * )
     * 
     * @return View
     *
     * @Annotations\View()
     * @Get("/kontzeptua/{id}")
     */
    public function getKontzeptua(Request $request, $id)
    {
        $_format = $request->get('_format','json');
        /** @var Kontzeptua $kontzeptua */
        $kontzeptua = $this->kontzeptuaRepo->find($id);
        if ( null === $kontzeptua ) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }        
        return $this->returnResponseDataAsFormat(str_replace(',', '.', $kontzeptua->getKopuruaProd()), $_format);
    }

    /**
     * Azterketen prezioa lortu kodea erabiliz
     * 
     * @OA\Response(
     *    response=200,
     *    description="Azterketen prezioa lortu kodea erabiliz",
     *    )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Ez da azterketa aurkitu"
     * )
     * 
     * @return View
     *
     * @Annotations\View()
     * @Get("/exam/{kodea}")
     */
    public function getExamPrices(Request $request, $kodea, $_format = "json")
    {
        $_format = $request->get('_format','json');
        /* 'Tasas según grupo azpiatalaren kodea azterketen prezioak bilatzeko
         * Gero erreziboen aplikazioan helbidea ezartzen da kontzeptu bakoitzeko
         * eta behar den zenbatekoa itzultzen du. Zenbatekoa baino ez du itzultzen.
         */
        $azterketaAzpiatala = $this->getParameter('azterketa_azpiatala');
        $kontzeptua = $this->kontzeptuaRepo->findOneBy([
            'azpiatala' => $azterketaAzpiatala,
            'kodea_prod' => $kodea,
        ]);
        if ( null === $kontzeptua ) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        return $this->returnResponseDataAsFormat(str_replace(',', '.', $kontzeptua->getKopuruaProd()), $_format);
    }

    private function returnResponseDataAsFormat($data, $_format = 'json', $template = null, $templateData = []) {
        $view = View::create();
        $view->setData($data);
        //dump($_format);die;
        if (null !== $_format && $_format === 'html' && null !== $template) {
            return $this->render($template,$templateData);
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