<?php

return [
    'router' => [
        'routes' => require __DIR__ . '/routes.config.php',
    ],
    'asset_manager' => require __DIR__ . '/asset.config.php',
    'service_manager' => [
        'factories' => [
        ],
        'abstract_factories' => [
            \Zend\Log\LoggerAbstractServiceFactory::class,
        ],
    ],
    'cli_commands' => [
        'factories' => [
        ],
    ],
    'command_handlers' => [
        'factories' => [
            \ConferenceTools\Checkin\Domain\CommandHandler\Delegate::class =>
                \ConferenceTools\Checkin\Service\Factory\CommandHandler\Delegate::class
        ],
    ],
    'process_managers' => [
        'factories' => [
            \ConferenceTools\Checkin\Domain\ProcessManager\ImportPurchase::class =>
                \ConferenceTools\Checkin\Service\Factory\ProcessManager\ImportPurchase::class,
        ],
    ],
    'command_subscriptions' => [
        \ConferenceTools\Checkin\Domain\Command\Delegate\CheckInDelegate::class =>
            \ConferenceTools\Checkin\Domain\CommandHandler\Delegate::class,
        \ConferenceTools\Checkin\Domain\Command\Delegate\RegisterDelegate::class =>
            \ConferenceTools\Checkin\Domain\CommandHandler\Delegate::class,
        \ConferenceTools\Checkin\Domain\Command\Delegate\UpdateDelegateInformation::class =>
            \ConferenceTools\Checkin\Domain\CommandHandler\Delegate::class,
    ],
    'event_listeners' => [
        'factories' => [
            \ConferenceTools\Checkin\AntiCorruption\TicketMappingListener::class =>
                \ConferenceTools\Checkin\AntiCorruption\TicketMappingListenerFactory::class
        ],
    ],
    'projections' => [
        'factories' => [
            \ConferenceTools\Checkin\Domain\Projection\Delegate::class =>
                \ConferenceTools\Checkin\Service\Factory\Projection\Delegate::class,
        ],
    ],
    'domain_event_subscriptions' => [
        \ConferenceTools\Checkin\Domain\Event\Delegate\DelegateCheckedIn::class => [
            \ConferenceTools\Checkin\Domain\Projection\Delegate::class,
        ],
        \ConferenceTools\Checkin\Domain\Event\Delegate\DelegateInformationUpdated::class => [
            \ConferenceTools\Checkin\Domain\Projection\Delegate::class,
        ],
        \ConferenceTools\Checkin\Domain\Event\Delegate\DelegateRegistered::class => [
            \ConferenceTools\Checkin\Domain\Projection\Delegate::class,
            \ConferenceTools\Checkin\Domain\ProcessManager\ImportPurchase::class,
        ],

        \ConferenceTools\Checkin\Domain\Event\Purchase\TicketPurchasePaid::class => [
            \ConferenceTools\Checkin\Domain\ProcessManager\ImportPurchase::class,
        ],
        \ConferenceTools\Checkin\Domain\Event\Purchase\TicketAssigned::class => [
            \ConferenceTools\Checkin\Domain\ProcessManager\ImportPurchase::class,
        ],
        \ConferenceTools\Checkin\Domain\Event\Purchase\TicketCreated::class => [
            \ConferenceTools\Checkin\Domain\ProcessManager\ImportPurchase::class,
        ],

        // EXTERNAL EVENTS
        \ConferenceTools\Tickets\Domain\Event\Ticket\TicketPurchasePaid::class => [
            \ConferenceTools\Checkin\AntiCorruption\TicketMappingListener::class,
        ],
        \ConferenceTools\Tickets\Domain\Event\Ticket\TicketAssigned::class => [
            \ConferenceTools\Checkin\AntiCorruption\TicketMappingListener::class,
        ],
        \ConferenceTools\Tickets\Domain\Event\Ticket\TicketReserved::class => [
            \ConferenceTools\Checkin\AntiCorruption\TicketMappingListener::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            \ConferenceTools\Checkin\Controller\CheckInController::class =>
                \ConferenceTools\Checkin\Controller\CheckInControllerFactory::class
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'input_filters' => [
        'abstract_factories' => [
        ],
    ],
    'input_filter_specs' => [
    ],
    'view_helpers' => [
        'invokables' => [
        ],
        'factories' => [
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => false,
        'display_exceptions' => false,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'checkin/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            'checkin/check-in/search' => __DIR__ . '/../view/checkin/checkin/search.phtml',
        ],
        'controller_map' => [
            'ConferenceTools\Checkin\Controller' => 'checkin',
        ],
    ],
    'zfc_rbac' => [
        'guards' => [
            'ZfcRbac\Guard\RouteGuard' => [
                'admin/*' => ['admin'],
            ],
        ],
    ],
    'doctrine' => [
        'driver' => [
            'conferencetools_checkin_read_orm_driver' => [
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Domain/ReadModel']
            ],
            'orm_default' => [
                'drivers' => [
                    'ConferenceTools\Checkin\Domain\ReadModel' => 'conferencetools_checkin_read_orm_driver',
                    'ConferenceTools\Checkin\Domain\ValueObject' => 'conferencetools_checkin_read_orm_driver',
                ],
            ],
        ],
    ],
    'log' => [
        'Log\\Application' => [
            'writers' => [
                [
                    'name' => 'syslog',
                ],
            ],
        ],
        'Log\\CommandBusLog'  => [
            'writers' => [
                [
                    'name' => 'syslog',
                ],
            ],
        ],
        'Log\\EventManagerLog'  => [
            'writers' => [
                [
                    'name' => 'syslog',
                ],
            ],
        ],
    ],
    'message_handlers' => [
        'CommandHandlerManager' => [
            'logger' => 'Log\\Application',
        ],
        'ProjectionManager' => [
            'logger' => 'Log\\Application',
        ],
        'EventListenerManager' => [
            'logger' => 'Log\\Application',
        ],
        'EventSubscriberManager' => [
            'logger' => 'Log\\Application',
        ],
    ],
];
