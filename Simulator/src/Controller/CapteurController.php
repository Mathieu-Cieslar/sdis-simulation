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
    public function putCapteur(Request $request, EntityManagerInterface $em ): JsonResponse {
        $data = $request->toArray();
        $capteurs = $em->getRepository(Capteur::class)->findAll();
        foreach ($capteurs as $capteur) {
            foreach ($data as $value){
                if ($value['id'] == $capteur->getId()){
                    $info  = new InfoCapteur();
                    $info->setDateInfo(new \DateTime());
                    $info->setValeur($value['valeur']);
                    $capteur->addInfo($info);
                    $em->persist($capteur);
                    $em->flush();
                }
            }
        }




    }


}