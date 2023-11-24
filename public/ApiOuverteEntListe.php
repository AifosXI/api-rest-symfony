<?php

namespace public;

var_dump($_SERVER['REQUEST_METHOD']);
var_dump($_SERVER['HTTP_ACCEPT']);
var_dump($_SERVER['CONTENT_TYPE']);
//var_dump($_SERVER);
var_dump($_GET);

if($_SERVER['REQUEST_METHOD'] === 'GET')
{
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
        if($companiesFiles)
        {
            if($_SERVER['CONTENT_TYPE'] === 'application/json')
            {
                $json = json_encode($companiesFiles);
                echo 'Liste des entreprises présentes sous format json <br/>';
                echo $json;
                return http_response_code(200);
            }
            else if ($_SERVER['CONTENT_TYPE'] === 'text/csv')
            {
                var_dump('csv format');
            }
            else {
                echo 'Format non prit en compte';
                return http_response_code(406);
            }
        }
        return $companiesFiles;
    } else {
        echo 'Aucune entreprise enregistrée';
        return http_response_code(200);
    }
} else {
    echo 'Mauvais "verbe utilisé"';
    return http_response_code(405);
}

?>