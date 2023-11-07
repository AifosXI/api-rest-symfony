<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use const http\Client\Curl\Features\HTTP2;

class HomeController extends AbstractController
{

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client) {
        $this->client = $client;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/search-company', name: 'search_company')]
    public function getCompanyByName(SerializerInterface $serializer) {
        $company = $this->client->request(
            'GET',
            'https://recherche-entreprises.api.gouv.fr/search?q=' . $_GET['company_name']
        );

        var_dump($company->getContent());

//        $companyJson = $serializer->serialize($company,'json');
//        return new JsonResponse($companyJson, 200, [], true);

        return $this->render('home/index.html.twig', [
            'company' => $company->getContent(),
        ]);
    }
}
