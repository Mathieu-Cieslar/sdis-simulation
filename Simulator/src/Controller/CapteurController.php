<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Entity\Capteur;
use App\Entity\Feu;
use App\Entity\InfoCapteur;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

#[AllowDynamicProperties]
class CapteurController extends AbstractController
{
    public function __construct(

    ) {
        $this->status = "ko";
    }

    #[Route(path: '/api/capteur', name: 'get_capteur', methods: "GET")]
    public function getAllCapteur(Request $request, EntityManagerInterface $em ): JsonResponse {
$data = $em->getRepository(Capteur::class)->findCapteursWithLastValue();

            return $this->json(
               $data
            );
    }

    #[Route(path: '/api/capteur', name: 'put_capteur', methods: "PUT")]
    public function putCapteur(Request $request, EntityManagerInterface $em, HubInterface $hub ): JsonResponse {
        $data = $request->toArray();
        $capteurs = $em->getRepository(Capteur::class)->findAll();
        foreach ($capteurs as $capteur) {
            foreach ($data as $value){
                if ($value['id'] == $capteur->getId()){
                    $info  = new InfoCapteur();
                    $info->setDateInfo(new \DateTime());
                    $info->setValeur($value['valeur']);
                    $capteur->addInfo($info);
                    $em->persist($info);
                    $em->persist($capteur);
                    $em->flush();
                }
            }
        }
        // Créer un événement pour Mercure
        $update = new Update(
            'https://example.com/new-fire', // Sujet unique
            json_encode(['capteur' => $data, 'nom' => 'Capteur updated'] )
        );
        // Envoyer l'événement au Hub Mercure
        $hub->publish($update);

        return $this->json(
            "Data updated"
        );
    }

    #[Route(path: '/api/capteur/actif', name: 'get_actif_capteur', methods: "GET")]
    public function getChangedCapteur(Request $request, EntityManagerInterface $em ): JsonResponse {
        $data = $em->getRepository(Capteur::class)->findChangedCapteursWithLastValue();

        return $this->json(
            $data
        );
    }


}