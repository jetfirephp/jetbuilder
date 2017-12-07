<?php return array(

    'middleware' => include 'app/middleware.php',
    'cron' => include 'app/cron.php',
    'intro' => include 'app/intro.php',
    'locales' => include 'app/locale.php',
    'events' => include 'app/event.php',
    
    'loader' =>
        array(
            'namespaces' =>
                array(
                    'Jet\\PublicBlock' => 'src/Blocks/PublicBlock',
                    'Jet\\FrontBlock' => 'src/Blocks/FrontBlock',
                    'Jet\\Modules' => 'src/Modules',
                    'Jet\\Themes' => 'src/Themes',
                ),
            'classes' =>
                array(),
        ),
    
    'blocks' =>
        array(
            'Public' =>
                array(
                    'path' => 'src/Blocks/PublicBlock/',
                    'route' => include ROOT . '/src/Blocks/PublicBlock/routes.php',
                    'namespace' => '\\Jet\\PublicBlock',
                    'view_dir' => 'src/Blocks/PublicBlock/Views/',
                    'model' => 'src/Models/'
                ),
            'Front' =>
                array(
                    'path' => 'src/Blocks/FrontBlock/',
                    'route' => include ROOT . '/src/Blocks/FrontBlock/routes.php',
                    'namespace' => '\\Jet\\FrontBlock',
                    'view_dir' => 'src/Blocks/FrontBlock/Views/'
                ),
        ),
    
    'fixtures' =>
        array(
            'src/DataFixtures/',
            /* Themes fixtures */
            'src/Themes/Aster/Fixtures/',
            'src/Themes/Balsamine/Fixtures/',
        ),
    
    'recaptcha' => array(
        'public_key' => '6LcqFCQTAAAAAAG_gnMpgTnE6809TroE30F4fMcp',
        'secret_key' => '6LcqFCQTAAAAAFCK_vTqCOS8slr8s5AI4Y3WwWNQ'
    )
);