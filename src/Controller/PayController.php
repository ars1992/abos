<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PayController extends AbstractController
{
    #[Route('/pay', name: 'app_pay')]
    public function list(): Response
    {
        
    }

    public function create(): Response
    {
        
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
