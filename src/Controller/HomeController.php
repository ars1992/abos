<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sub;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController 
{
    #[Route('/home', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $sub = $entityManager->getRepository(Sub::class)->findAll();

        if( ! $sub)
        {
            return $this->json(["succes" => false], 418);
        }

        $dataArray = [
            "success" => false,
            "sub" => $sub,
        ];

        return $this->json($dataArray);
    }

    public function addSub(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subName = $request->request->get("name");
        $subStartdate = $request->request->get("startdate");

        $sub = (new Sub)
        ->setName($subName)
        ->setStartDate(new DateTime($subStartdate));
        
        $entityManager->persist($sub);
        $entityManager->flush();

        if($sub->getId())
        {
            return $this->json(["succes" => true, "sub" => $sub], 201);
        }

        return $this->json(["succes" => false], 418);
    }
}
