<?php

if (!function_exists('geocode')) {

    /**
     * @param $address
     * @return array|bool
     */
    function geocode($address){

        $address = urlencode($address);
        $url = "https://maps.google.com/maps/api/geocode/json?sensor=false&address={$address}";
        $resp_json = file_get_contents($url);
        $resp = json_decode($resp_json, true);
        if($resp['status']=='OK') {
            $lati = $resp['results'][0]['geometry']['location']['lat'];
            $longi = $resp['results'][0]['geometry']['location']['lng'];
            $formatted_address = $resp['results'][0]['formatted_address'];
            if ($lati && $longi && $formatted_address) {
                $data_arr = array();
                array_push($data_arr, $lati, $longi, $formatted_address);
                return $data_arr;
            }
        }
        return false;
    }
}

if (!function_exists('findIndex')) {

    /**
     * @param array $array
     * @param $key
     * @param $value
     * @return bool|int|string
     */
    function findIndex($array = [], $key, $value)
    {

        foreach ($array as $index => $item) {
            if ($item[$key] === $value)
                return $index;
        }
        return false;
    }
}

if ( ! function_exists('glob_recursive'))
{
    // Does not support flag GLOB_BRACE

    function glob_recursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);

        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
        {
            $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
        }

        return $files;
    }
}

if (function_exists('mb_get_info')) {
    if (!function_exists('twig_truncate_filter')) {
        function twig_truncate_filter(Twig_Environment $env, $value, $length = 30, $preserve = false, $separator = '...')
        {
            if (mb_strlen($value, $env->getCharset()) > $length) {
                if ($preserve) {
                    // If breakpoint is on the last word, return the value without separator.
                    if (false === ($breakpoint = mb_strpos($value, ' ', $length, $env->getCharset()))) {
                        return $value;
                    }
                    $length = $breakpoint;
                }
                return rtrim(mb_substr($value, 0, $length, $env->getCharset())) . $separator;
            }
            return $value;
        }
    }

    if (!function_exists('twig_wordwrap_filter')) {
        function twig_wordwrap_filter(Twig_Environment $env, $value, $length = 80, $separator = "\n", $preserve = false)
        {
            $sentences = array();
            $previous = mb_regex_encoding();
            mb_regex_encoding($env->getCharset());
            $pieces = mb_split($separator, $value);
            mb_regex_encoding($previous);
            foreach ($pieces as $piece) {
                while (!$preserve && mb_strlen($piece, $env->getCharset()) > $length) {
                    $sentences[] = mb_substr($piece, 0, $length, $env->getCharset());
                    $piece = mb_substr($piece, $length, 2048, $env->getCharset());
                }
                $sentences[] = $piece;
            }
            return implode($separator, $sentences);
        }
    }
} else {
    if (!function_exists('twig_truncate_filter')) {
        function twig_truncate_filter(Twig_Environment $env, $value, $length = 30, $preserve = false, $separator = '...')
        {
            if (strlen($value) > $length) {
                if ($preserve) {
                    if (false !== ($breakpoint = strpos($value, ' ', $length))) {
                        $length = $breakpoint;
                    }
                }
                return rtrim(substr($value, 0, $length)) . $separator;
            }
            return $value;
        }
    }

    if (!function_exists('twig_wordwrap_filter')) {
        function twig_wordwrap_filter(Twig_Environment $env, $value, $length = 80, $separator = "\n", $preserve = false)
        {
            return wordwrap($value, $length, $separator, !$preserve);
        }
    }
}

if (!function_exists('delTree')) {
    function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}