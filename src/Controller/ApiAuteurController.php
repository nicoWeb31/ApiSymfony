<?php

namespace App\Controller;

use App\Entity\Auteurs;
use App\Repository\AuteursRepository;
use App\Repository\NationaliteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiAuteurController extends AbstractController
{
    /**
     * @Route("/api/auteurs", name="api_auteurs",methods="GET")
     */
    public function list(AuteursRepository $repo, SerializerInterface $serializer)
    {
        $auteurs = $repo->findAll();
        $resultInJson = $serializer->serialize($auteurs, 'json', [
            'groups' => ['listeAuteursFull']
        ]);

        return new JsonResponse($resultInJson, 200, [], true);
    }

    /**
     * @Route("/api/auteurs/{id}", name="api_auteur_show",methods="GET")
     */
    public function show(Auteurs $auteurs, SerializerInterface $serializer)
    {

        $resultInJson = $serializer->serialize($auteurs, 'json', [
            'groups' => ['listeAuteursSimple']
        ]);

        return new JsonResponse($resultInJson, Response::HTTP_OK, [], true);
    }


    /**
     * @Route("/api/auteurs", name="api_auteur_create",methods="POST")
     */
    public function create(NationaliteRepository $repoNatio, SerializerInterface $serializer,Request $req,EntityManagerInterface $man,ValidatorInterface $val)
    {
        $data = $req->getContent();
        $dataTab= $serializer->decode($data,'json');
        $auteurs = new Auteurs();
        $nationalite =$repoNatio->find($dataTab['Nationalite']['id']);
        
        $serializer->deserialize($data,Auteurs::class,'json',['object_to_populate' => $auteurs]);
        $auteurs->setNationalite($nationalite);


        //gestion des erreurs
        $error=$val->validate($auteurs);
        if(count($error)){
            $errorJson = $serializer->serialize($error,'json');
        return new JsonResponse($errorJson,Response::HTTP_BAD_REQUEST,[],true);
        }

        $man->persist($auteurs);
        $man->flush();

        return new JsonResponse("auteurs bien crées",Response::HTTP_CREATED,[
            "location"=>"api/auteurss/".$auteurs->getId()
        ],true);
    }

    /**
     * @Route("/api/auteurs/{id}", name="api_auteur_update",methods="PUT")
     */
    public function update(NationaliteRepository $repoNatio, Auteurs $auteurs, SerializerInterface $serializer, Request $req, EntityManagerInterface $man, ValidatorInterface $val)
    {
        $data = $req->getContent();
        $dataTab = $serializer->decode($data, 'json');
        $nationalite = $repoNatio->find($dataTab['Nationalite']['id']);
        //cas ou les objets sont imbriquer
        $serializer->deserialize($data, Auteurs::class, 'json', ['object_to_populate' => $auteurs]);
        $auteurs->setNationalite($nationalite);

        //cas des objet les un apres les autres
        // $serializer->denormalize($dataTab['auteurs'],Auteurs::class,null,['object_to_populate' => $auteurs]);


        //gestion des erreurs
        $error = $val->validate($auteurs);
        if (count($error)) {
            $errorJson = $serializer->serialize($error, 'json');
            return new JsonResponse($errorJson, Response::HTTP_BAD_REQUEST, [], true);
        }


        $man->persist($auteurs);
        $man->flush();

        return new JsonResponse("modif ok", Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/auteurs/{id}", name="api_auteurs_delete",methods="DELETE")
     */
    public function delete(Auteurs $auteurs, EntityManagerInterface $man)
    {
        $man->remove($auteurs);
        $man->flush();

        return new JsonResponse("supp ok ok", Response::HTTP_OK, []);
    }
}
