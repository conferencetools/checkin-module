<?php

use ConferenceTools\Checkin\AntiCorruption;
use ConferenceTools\Checkin\Cli\Command as CliCommand;
use ConferenceTools\Checkin\Controller;
use ConferenceTools\Checkin\Domain\Command;
use ConferenceTools\Checkin\Domain\CommandHandler;
use ConferenceTools\Checkin\Domain\Event;
use ConferenceTools\Checkin\Domain\ProcessManager;
use ConferenceTools\Checkin\Domain\Projection;
use ConferenceTools\Checkin\Service\Factory;

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
            CliCommand\GenerateQR::class => CliCommand\GenerateQRFactory::class,
        ],
    ],
    'command_handlers' => [
        'factories' => [
            CommandHandler\Delegate::class => Factory\CommandHandler\Delegate::class
        ],
    ],
    'process_managers' => [
        'factories' => [
            ProcessManager\ImportPurchase::class => Factory\ProcessManager\ImportPurchase::class,
        ],
    ],
    'command_subscriptions' => [
        Command\Delegate\CheckInDelegate::class => CommandHandler\Delegate::class,
        Command\Delegate\RegisterDelegate::class => CommandHandler\Delegate::class,
        Command\Delegate\UpdateDelegateInformation::class => CommandHandler\Delegate::class,
    ],
    'event_listeners' => [
        'factories' => [
            AntiCorruption\TicketMappingListener::class => AntiCorruption\TicketMappingListenerFactory::class
        ],
    ],
    'projections' => [
        'factories' => [
            Projection\Delegate::class => Factory\Projection\Delegate::class,
        ],
    ],
    'domain_event_subscriptions' => [
        Event\Delegate\DelegateCheckedIn::class => [
            Projection\Delegate::class,
        ],
        Event\Delegate\DelegateInformationUpdated::class => [
            Projection\Delegate::class,
        ],
        Event\Delegate\DelegateRegistered::class => [
            Projection\Delegate::class,
            ProcessManager\ImportPurchase::class,
        ],

        Event\Purchase\TicketPurchasePaid::class => [
            ProcessManager\ImportPurchase::class,
        ],
        Event\Purchase\TicketAssigned::class => [
            ProcessManager\ImportPurchase::class,
        ],
        \ConferenceTools\Checkin\Domain\Event\Purchase\TicketCreated::class => [
            \ConferenceTools\Checkin\Domain\ProcessManager\ImportPurchase::class,
        ],

        // EXTERNAL EVENTS
        \ConferenceTools\Tickets\Domain\Event\Ticket\TicketPurchasePaid::class => [
            AntiCorruption\TicketMappingListener::class,
        ],
        \ConferenceTools\Tickets\Domain\Event\Ticket\TicketAssigned::class => [
            AntiCorruption\TicketMappingListener::class,
        ],
        \ConferenceTools\Tickets\Domain\Event\Ticket\TicketReserved::class => [
            \ConferenceTools\Checkin\AntiCorruption\TicketMappingListener::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\CheckInController::class => Controller\CheckInControllerFactory::class
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
