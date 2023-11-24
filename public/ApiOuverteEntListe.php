<?php

namespace public;

if($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET['siren']))
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
                echo 'Format non pris en compte';
                return http_response_code(406);
            }
        }
        return $companiesFiles;
    } else {
        echo 'Aucune entreprise enregistrée';
        return http_response_code(200);
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['siren'])) {
    $siren = $_GET['siren'];
    $fileName = 'entreprise_' . $siren . '.json';
    $isFile = is_file($fileName);

    if(!$isFile)
    {
        echo 'Aucune entreprise avec ce SIREN';
        return http_response_code(404);
    }

    $fileContent = file_get_contents($fileName);

    echo $fileContent;
    return http_response_code(200);
}
else {
    echo 'Mauvais "verbe" utilisé';
    return http_response_code(405);
}

?>