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

    #[Route('/companies', name: 'search_companies')]
    public function index(): Response
    {
        return $this->render('entreprise/index.html.twig');
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

        $session = $request->getSession();

        $session->set('entreprise', $siren);

        $companyJson = $this->client->request(
            'GET',
            'https://recherche-entreprises.api.gouv.fr/search?q=' . $siren
        );

        $filename = 'entreprise_' . $siren . '.json';
        $file = fopen($filename, 'w');
        $txt = $companyJson->getContent();
        fwrite($file, $txt);
        fclose($file);


        $company = json_decode($companyJson->getContent());

        return $this->render('entreprise/show.html.twig', [
            'company' => $company,
            'filename' => $filename
        ]);
    }

    #[Route('/entreprise/download', name: 'entreprise_download')]
    public function downloadCompanyInfo(Request $request) {

        $session = $request->getSession();

        $filename = 'entreprise_' . $session->get('entreprise') . '.json ';

        $url = realpath($filename);


        if(is_file($filename))
        {
            $response = new Response();
            $response->headers->set('Content-type', 'application/json');
            $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));
            $response->setContent(file_get_contents($url));
            $response->setStatusCode(200);
            $response->headers->set('Content-Transfer-Encoding', 'binary');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            return $response;
        } else {
            return $this->render('error.html.twig', [
                'error' => 'Le fichier n\'existe pas',
            ]);
        }
    }
}
