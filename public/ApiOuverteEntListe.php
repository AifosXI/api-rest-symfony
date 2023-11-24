<?php

namespace public;

//use Symfony\Component\Finder\Finder;

//$finder = new Finder();
//$finder->files()->name('.json')->notName('entreprise_index.html.twig.json')->in(__DIR__ . '/public');

$dir = __DIR__;

$files = scandir($dir);

$companiesFiles = [];

if($files) {
    foreach ($files as $file) {
        $fileType = filetype($file);
        if($fileType === 'file')
        {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if($ext === 'json')
            {
                if($file !== 'entreprise_index.html.twig.json' && $file !== 'entreprise.json')
                {
                    array_push($companiesFiles, $file);
                }
            }
        }
    }
    var_dump($companiesFiles);
    return $companiesFiles;
}

//if ($finder->hasResults()) {
//    foreach ($finder as $file) {
//        var_dump($file->getRealPath());
//    }
//}
//var_dump('hello');
//echo 'yes'
?>