<?php

namespace App\Controller;

use App\Entity\Feu;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class FeuController extends AbstractController
{
    #[Route('/api/feu', name: 'get_feu', methods: ['GET'])]
    public function getAllFeu(EntityManagerInterface $em): JsonResponse
    {
        $data = $em->getRepository(Feu::class)->findAll();
        return $this->json($data);
    }

    #[Route(path: '/api/feu', name: 'post_feu', methods: "POST")]
    public function postFeu(\Symfony\Component\HttpFoundation\Request $request, EntityManagerInterface $em,HubInterface $hub): JsonResponse
    {
        $data = $request->toArray();
        $feu = new Feu();
        $feu->setCoorX($data['coorX']);
        $feu->setCoorY($data['coorY']);
        $feu->setIntensite($data['intensite']);
        $em->persist($feu);
        $em->flush();

        return $this->json(
            $data
        );

    }


//    #[Route('/api/update', name: 'get_update', methods: ['GET'])]
//    public function notifyNewFeu(HubInterface $hub): JsonResponse
//    {
//        // Créer un événement pour Mercure
//        $update = new Update(
//            'https://example.com/new-fire', // Sujet unique
//            json_encode(['coorX' => 46.764, 'coorY' => 4.835, 'nom' => 'Feu simulé'])
//        );
//        // Envoyer l'événement au Hub Mercure
//        $hub->publish($update);
//        return new JsonResponse(['status' => 'success']);
//    }
}
