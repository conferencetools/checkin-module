<?php

return [
    'checkin' => [
        'type' => \Zend\Mvc\Router\Http\Literal::class,
        'options' => [
            'route' => ''
        ],
        'child_routes' =>[
            'search' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '[/]',
                    'defaults' => [
                        'controller' => \ConferenceTools\Checkin\Controller\CheckInController::class,
                        'action' => 'search',
                    ],
                ],
            ],
            'checkin' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/checkin/:delegateId',
                    'defaults' => [
                        'controller' => \ConferenceTools\Checkin\Controller\CheckInController::class,
                        'action' => 'checkin',
                    ],
                ],
            ],
        ]
    ]
];
