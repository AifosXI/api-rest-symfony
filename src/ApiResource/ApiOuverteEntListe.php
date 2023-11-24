<?php

namespace App\ApiResource;

use Symfony\Component\Finder\Finder;

class ApiOuverteEntListe {

    public function GetSavedCompany() {
        $finder = new Finder();
        $finder->files()->name('.json')->notName('entreprise_index.html.twig.json')->in(__DIR__ . '/public');

        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                var_dump($file->getRealPath());
            }
        }
    }

}