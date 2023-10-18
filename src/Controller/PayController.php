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
        $payType = (new Pay);
        $requestData = $request->request->all();
        $this->setDataToClass($requestData, $payType);

        $errors = $validator->validate($payType);

        if (count($errors) <= 0) {
            $entityManager->persist($payType);
            $entityManager->flush();
            return $this->json(["succes" => true, "pay" => $payType], 201);
        }

        return $this->render('author/validation.html.twig', [
            'errors' => $errors,
        ]);
    }

    public function read(): Response
    {
        
    }

    public function update(int $id, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $payType = $entityManager->getRepository(Pay::class)->find($id);
        
        if ( ! $payType){
            return $this->json([], 418);
        }

        $requestData = $request->request->all();
        $this->setDataToClass($requestData, $payType);

        $errors = $validator->validate($payType);

        if (count($errors) <= 0) {
            $entityManager->flush();
            return $this->json(["succes" => true, "pay" => $payType], 201);
        }

        return $this->json([])
    }

    public function delete(): Response
    {
        
    }

    private function setDataToClass(array $requestData, object $object)
    {
        foreach($requestData as $key => $data){
            $methodName = "set" . ucfirst($key);
            if( ! empty($data) && method_exists($object, $methodName)){
                $object->{$methodName}($data);
            }
        }
    }

}
