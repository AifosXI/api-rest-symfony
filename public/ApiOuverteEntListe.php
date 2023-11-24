<?php

namespace public;

var_dump($_SERVER['REQUEST_METHOD']);
var_dump($_SERVER['HTTP_ACCEPT']);

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
        return $companiesFiles;
    }
} else {
    return http_response_code(405);
}

?>