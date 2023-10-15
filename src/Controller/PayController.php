<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Pay;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;

class PayController extends AbstractController
{
    #[Route('/pay', name: 'app_pay')]
    public function list(EntityManagerInterface $entityManager): Response
    {
         $payTybes = $entityManager->getRepository(Pay::class)->findAll();
         return $this->json(["payTypes" => $payTybes]);
    }

    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $payName = $request->request->get("name");
        $description = $request->request->get("description", "");

        $pay = (new Pay)
            ->setName($payName)
            ->setDescription($description);

        $errors = $validator->validate($pay);

        if (count($errors) <= 0) {
            $entityManager->persist($pay);
            $entityManager->flush();
            return $this->json(["succes" => true, "pay" => $pay], 201);
        }

        return $this->render('author/validation.html.twig', [
            'errors' => $errors,
        ]);
        
    }

    public function read(): Response
    {
        
    }

    public function update(): Response
    {
        
    }

    public function delete(): Response
    {
        
    }

}
