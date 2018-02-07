<?php

return [
    'checkin' => [
        'type' => 'Segment',
        'options' => [
            'route' => '/',
            'defaults' => [
                'controller' => \ConferenceTools\Checkin\Controller\CheckInController::class,
                'action' => 'search',
            ],
        ],
        'may_terminate' => true,
        'child_routes' => [
            'checkin' => [
                'type' => 'Segment',
                'options' => [
                    'route' => 'checkin/:delegateId',
                    'defaults' => [
                        'action' => 'checkin',
                    ],
                ],
            ],
        ],
    ],
];
