<?php

namespace App\Controller;

use App\Entity\BU;
use App\Entity\Structure;
use App\Entity\Utilisateur;
use App\Form\BUType;
use App\Form\StructureType;
use App\Form\UtilisateurType;
use App\Repository\BURepository;
use App\Repository\StructureRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DataController extends AbstractController
{
    /**
     * @var string
     */
    private $code="code";
    /**
     * @var string
     */
    private $statut="statut";
    /**
     * @var string
     */
    private $data="data";
    /**
     * @Route("/data", name="data")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DataController.php',
        ]);
    }

    /**
     * @Route("/ajout", name="ajout")
     */
    public function add(Request $request,UtilisateurRepository $utilisateurRepository,BURepository $bURepository,StructureRepository $structureRepository){
        $values=$request->request->all();
        $em = $this->getDoctrine()->getManager();
        $userEmail=$utilisateurRepository->findOneBy([Utilisateur::EMAIL=>$values["email"]]);
        $userMatricule=$utilisateurRepository->findOneBy([Utilisateur::MATRICULE=>$values["matricule"]]);
        $userTel=$utilisateurRepository->findOneBy([Utilisateur::TELEPHONE=>$values["telephone"]]);
        $existingBu=$bURepository->findOneBy([BU::LIBELLEBU=>$values["libelleBU"]]);
        $existingStructure=$structureRepository->findOneBy(["libelleStructure"=>$values["libelleStructure"]]);
        $parent=$structureRepository->findOneBy([Structure::LIBELLESTRUCTURE=>$values["parent"]]);
        if($userEmail || $userMatricule || $userTel){
            return new JsonResponse([
                $this->code=>205,
                $this->statut=>false,
                $this->data=>"user already exist"]);
        }
        if(!empty($values["parent"]) && !$parent){
            return new JsonResponse([
                $this->code=>205,
                $this->statut=>false,
                $this->data=>"parent doesn't exist"]);
        }
        $bu=new BU();
        $form = $this->createForm(BUType::class, $bu);
        $form->handleRequest($request);
        $form->submit($values);
        $structure=new Structure();
        $form = $this->createForm(StructureType::class, $structure);
        $form->handleRequest($request);
        $form->submit($values);
        $structure->setBU($bu);
        $utilisateur=new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
        $form->submit($values);
        $utilisateur->setStructure($structure);
        $utilisateur->setRoles(["ROLE_ADMIN"]);
        if($existingBu && $existingStructure){
            $utilisateur->setStructure($existingStructure);
            $em->persist($utilisateur);
            $em->flush();
            return new JsonResponse([
                $this->code=>201,
                $this->statut=>true,
                $this->data=>"Utilisateur ajouté avec succes"]);
        }
        else if($existingBu && !$existingStructure){
            $structure->setBU($existingBu);
            $em->persist($structure);
            $em->persist($utilisateur);
            $em->flush();
            return new JsonResponse([
                $this->code=>201,
                $this->statut=>true,
                $this->data=>"Ajout effectué avec succes"]);
        }
       else if(!$existingBu && $existingStructure){
            return new JsonResponse([
                $this->code=>201,
                $this->statut=>true,
                $this->data=>"Cette structure est deja rataché a une autre BU"]);
        }
        $em->persist($bu);
        $em->persist($structure);
        $em->persist($utilisateur);
        $em->flush();
        return new JsonResponse([
            $this->code=>201,
            $this->statut=>true,
            $this->data=>"Ajout effectué avec succes"]);
    }
}
