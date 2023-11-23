<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntrepriseCalcul extends AbstractController{

    #[Route('/search-salary', name: 'search_salary')]
    public function getSalary(Request $request): Response
    {
        $param = $request->get('company_name');
        dd($param);
    }
}