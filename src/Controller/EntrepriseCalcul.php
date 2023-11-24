<?php

namespace App\Controller;

use App\Service\SalaryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class EntrepriseCalcul extends AbstractController{

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client) {
        $this->client = $client;
    }

    #[Route('/search-salary', name: 'search_salary', methods: ['GET'])]
    public function getSalary(Request $request, SalaryService $salaryService): Response
    {
        $salary = $request->query->get('salary');

        $salaryTwig = $salaryService->getTotalSalary($salary);

        return $this->render('salaire/result.html.twig', [
            'salary' => $salaryTwig,
        ]);
    }
}