<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sub;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): JsonResponse
    {
        $dataArray = [
            "success" => false,
            "sub" => $this->generateSubs(),
        ];

        return $this->json($dataArray);
    }

    protected function generateSubs(): array
    {
        $returnArray = [];

        $sub = (new Sub)
        ->setName("Netflix")
        ->setStartDate(new \DateTime);

        $returnArray[] = $sub;


        return $returnArray;
    }
}
