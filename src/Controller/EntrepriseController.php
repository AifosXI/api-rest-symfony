<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class EntrepriseController extends AbstractController
{

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client) {
        $this->client = $client;
    }

    #[Route('/search-company', name: 'search_company', methods: ['GET'])]
    public function getCompanyByName(Request $request) {

        $param = $request->get('company_name');

        $companiesJson = $this->client->request(
            'GET',
            'https://recherche-entreprises.api.gouv.fr/search?q=' . $param
        );

        $companies = json_decode($companiesJson->getContent());


        return $this->render('entreprise/index.html.twig', [
            'companies' => $companies,
        ]);
    }

    #[Route('/entreprise/{siren}', name: 'entreprise_siren', methods: ['GET'])]
    public function getCompanyBySiren(Request $request) {

        $siren = $request->get('siren');

        $companyJson = $this->client->request(
            'GET',
            'https://recherche-entreprises.api.gouv.fr/search?q=' . $siren
        );

        $company = json_decode($companyJson->getContent());

        return $this->render('entreprise/show.html.twig', [
            'company' => $company,
        ]);
    }
}
