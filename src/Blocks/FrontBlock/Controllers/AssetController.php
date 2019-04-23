<?php

namespace Jet\FrontBlock\Controllers;


class AssetController
{

    public function asset($path){
        if(strtolower(substr($path, -4)) == '.css'){
            header('Content-type: text/css');
        }elseif(strtolower(substr($path, -3)) == '.js'){
            header('Content-type: application/javascript');
        }else {
            header('Content-type: ' . mime_content_type(ROOT . '/'. $path));
        }
        echo is_file($path) ? file_get_contents($path) : '';
    }
}