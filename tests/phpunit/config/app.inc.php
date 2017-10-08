<?php return array(

    'loader' =>
        array(
            'namespaces' =>
                array(
                    'Jet' => 'src',
                    'Jet\\PublicBlock' => 'src/Blocks/PublicBlock',
                    'Jet\\ApiBlock' => 'src/Blocks/ApiBlock',
                    'Jet\\AdminBlock' => 'src/Blocks/AdminBlock',
                    'Jet\\FrontBlock' => 'src/Blocks/FrontBlock',
                    'Jet\\Modules' => 'src/Modules',
                    'Jet\\Themes' => 'src/Themes',
                ),
            'classes' =>
                array(),
        ),
    'blocks' =>
        array(
            'Front' =>
                array(
                    'path' => 'src/Blocks/FrontBlock/',
                    'namespace' => '\\Jet\\FrontBlock',
                    'view_dir' => 'src/Blocks/FrontBlock/Views/',
                ),
            'Admin' =>
                array(
                    'path' => 'src/Blocks/AdminBlock/',
                    'namespace' => '\\Jet\\AdminBlock',
                    'view_dir' => 'src/Blocks/AdminBlock/Views/',
                    'prefix' => 'admin',
                ),
            'Api' =>
                array(
                    'path' => 'src/Blocks/ApiBlock/',
                    'namespace' => '\\Jet\\ApiBlock',
                    'view_dir' => 'src/Blocks/ApiBlock/Views/',
                    'prefix' => 'api'
                ),
            'Public' =>
                array(
                    'path' => 'src/Blocks/PublicBlock/',
                    'namespace' => '\\Jet\\PublicBlock',
                    'view_dir' => 'src/Blocks/PublicBlock/Views/',
                    'model' => 'src/Models/',
                    'prefix' => ':_locale',
                    'subdomain' => ''
                ),
        ),
    'fixtures' =>
        array(
            'src/DataFixtures/',
            /* Themes fixtures */
            'src/Themes/Aster/Fixtures/',
            'src/Themes/Balsamine/Fixtures/',
            'src/Themes/Heliotrope/Fixtures/',
        ),
    'response' =>
        array(
            404 => 'Response/404.html.twig',
            405 => 'Response/405.html.twig',
            500 => 'Response/500.html.twig',
            503 => 'Response/503.html.twig',
        ),
    'admin_libs' => array(
        'css' => array(
            'libs/bootstrap/bootstrap.css',
            'libs/material/materialadmin.css',
            'libs/material/material-design-iconic-font.min.css',
            'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
            'https://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900',
        ),
        'js' => array(
            'https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyArNGSSl-KPS23l24EilPNQhfmEw_V8BtI&libraries=places&region=FR'
        )
    ),
    'locales' => include 'app/locale.php',
    'events' => include 'app/event.php',
    'settings' => include 'app/setting.php',
    'scripts' =>
        array(
            'npm' =>
                array(
                    'enable' => false,
                    'build' => 'npm run build --base=webpack.admin',
                ),
        ),
);