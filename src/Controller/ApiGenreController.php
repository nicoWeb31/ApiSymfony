<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="api_genres",methods="GET")
     */
    public function list(GenreRepository $repo,SerializerInterface $serializer)
    {
        $genres = $repo->findAll();
        $resultInJson = $serializer->serialize($genres,'json',[
            'groups'=>['listeGenreFull']
        ]);
    
        return new JsonResponse($resultInJson,200,[],true);
    }

    /**
     * @Route("/api/genres/{id}", name="api_genres-show",methods="GET")
     */
    public function show(Genre $genre,SerializerInterface $serializer)
    {
        
        $resultInJson = $serializer->serialize($genre,'json',[
            'groups'=>['listeGenreSimple']
        ]);
    
        return new JsonResponse($resultInJson,Response::HTTP_OK,[],true);
    }


    /**
     * @Route("/api/genres", name="api_genres_show",methods="POST")
     */
    public function create(SerializerInterface $serializer,Request $req,EntityManagerInterface $man,ValidatorInterface $val)
    {
        $data = $req->getContent();
        $genre = $serializer->deserialize($data,Genre::class,'json');
        //gestion des erreurs
        $error=$val->validate($genre);
        if(count($error)){
            $errorJson = $serializer->serialize($error,'json');
        return new JsonResponse($errorJson,Response::HTTP_BAD_REQUEST,[],true);
        }
        
        $man->persist($genre);
        $man->flush();

        return new JsonResponse("genre bien crées",Response::HTTP_CREATED,[
            "location"=>"api/genres/".$genre->getId()
        ],true);
    }

    /**
     * @Route("/api/genres/{id}", name="api_genres_update",methods="PUT")
     */
    public function update(Genre $genre,SerializerInterface $serializer, Request $req,EntityManagerInterface $man,ValidatorInterface $val)
    {
        $data = $req->getContent();


        $serializer->deserialize($data,Genre::class,'json',['object_to_populate'=>$genre]);

         //gestion des erreurs
    $error=$val->validate($genre);
        if(count($error)){
            $errorJson = $serializer->serialize($error,'json');
    return new JsonResponse($errorJson,Response::HTTP_BAD_REQUEST,[],true);
    }
        

        $man->persist($genre);
        $man->flush();
    
        return new JsonResponse("modif ok",Response::HTTP_OK,[],true);
    }

        /**
     * @Route("/api/genres/{id}", name="api_genres_delete",methods="DELETE")
     */
    public function delete(Genre $genre,EntityManagerInterface $man)
    {
        $man->remove($genre);
        $man->flush();
    
        return new JsonResponse("supp ok ok",Response::HTTP_OK,[]);
    }
}
