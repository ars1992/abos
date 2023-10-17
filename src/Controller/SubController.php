<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sub;
use App\Entity\Pay;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SubController extends AbstractController
{
    #[Route('/subs', name: 'app_sub')]
    public function list(EntityManagerInterface $entityManager, RouterInterface $router): Response
    {
        $subs = $entityManager->getRepository(Sub::class)->findAll();
        if (!$subs) {
            return $this->json(["succes" => false], 418);
        }

        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, []);

        $subsCollection = [];
        foreach($subs as $sub){
            $array = $serializer->normalize($sub, null, ["circular_reference_handler" => function ($object) {
                return $object->getId();
            }]);
            $subsCollection[] = $array;
        }

        $dataArray = [
            "data" => $subsCollection,
            "links" => $router->generate("listSubs"),
        ];

        return $this->json($dataArray);
    }

    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $subName = $request->request->get("name");
        $subStartdate = $request->request->get("startDate");

        $sub = (new Sub)
            ->setName($subName)
            ->setStartDate(new DateTime($subStartdate));

        $errors = $validator->validate($sub);

        if (count($errors) <= 0) {
            $entityManager->persist($sub);
            $entityManager->flush();
            return $this->json(["succes" => true, "sub" => $sub], 201);
        }

        return $this->render('author/validation.html.twig', [
            'errors' => $errors,
        ]);
    }

    public function read(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $sub = $entityManager->getRepository(Sub::class)->find($id);
        if ($sub) {
            return $this->json(["data" => $sub], 200);
        }
        return $this->json([], 418);
    }

    public function update(int $id, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $subType = $entityManager->getRepository(Sub::class)->find($id);
        if (!$subType) {
            return $this->json([], 418);
        }


        // $payId = (int) $request->request->get("payType");
        // if($payId){
        //     $payType = $entityManager->getRepository(Pay::class)->find($payId);
        //     if($payType){
        //    // in Symfony 6 nicht möglich
        //         $request->request->set("payType", array($payType));
        //     } else {
        //         $request->request->remove("payType");
        //     }
        // }

        $requestData = $request->request->all();
        $this->setDataToClass($requestData, $subType);

        $errors = $validator->validate($subType);

        if (count($errors) <= 0) {
            $entityManager->flush();
            return $this->json(["succes" => true, "data" => $subType], 201);
        }

        return $this->render('author/validation.html.twig', [
            'errors' => $errors,
        ]);
    }

    private function setDataToClass(array $requestData, object $object)
    {
        foreach ($requestData as $key => $data) {
            $methodName = "set" . ucfirst($key);
            if (!empty($data) && method_exists($object, $methodName)) {
                $object->{$methodName}($data);
            }
        }
    }
}
