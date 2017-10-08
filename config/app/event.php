<?php
return array(
    'createLog' => array(
        array(
            'type' => 'async', // linear or async
            'method' => 'POST',
            'route' => 'api.log.create',
        )
    ),
    'updateMedia' => array(
        array(
            'type' => 'async', // linear or async
            'method' => 'POST',
            'route' => 'api.media.update',
        )
    )
);