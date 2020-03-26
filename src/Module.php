<?php
/**
 * Module.php - Module Class
 *
 * Module Class File for Basket Module
 *
 * @category Config
 * @package Basket
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.1
 * @since 1.0.0
 */

namespace OnePlace\Basket;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Mvc\MvcEvent;
use Laminas\ModuleManager\ModuleManager;
use Laminas\Session\Config\StandardConfig;
use Laminas\Session\SessionManager;
use Laminas\Session\Container;
use Application\Controller\CoreEntityController;

class Module {
    /**
     * Module Version
     *
     * @since 1.0.0
     */
    const VERSION = '1.0.4';

    /**
     * Load module config file
     *
     * @since 1.0.0
     * @return array
     */
    public function getConfig() : array {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Load Models
     */
    public function getServiceConfig() : array {
        return [
            'factories' => [
                # Basket Module - Base Model
                Model\BasketTable::class => function($container) {
                    $tableGateway = $container->get(Model\BasketTableGateway::class);
                    return new Model\BasketTable($tableGateway,$container);
                },
                Model\BasketTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Basket($dbAdapter));
                    return new TableGateway('basket', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    /**
     * Load Controllers
     */
    public function getControllerConfig() : array {
        return [
            'factories' => [
                # Basket Main Controller
                Controller\BasketController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    $tableGateway = $container->get(Model\BasketTable::class);
                    $oBasketStepTbl = new TableGateway('basket_step', $oDbAdapter);
                    $oBasketPosTbl = new TableGateway('basket_position', $oDbAdapter);
                    $oJobTbl = $container->get(\OnePlace\Job\Model\JobTable::class);
                    $oJobPosTbl = new TableGateway('job_position', $oDbAdapter);
                    return new Controller\BasketController(
                        $oDbAdapter,
                        $container->get(Model\BasketTable::class),
                        $container,
                        [
                            'basket-step' => $oBasketStepTbl,
                            'basket-position' => $oBasketPosTbl,
                            'job' => $oJobTbl,
                            'job-position' => $oJobPosTbl,
                        ]
                    );
                },
                # Api Plugin
                Controller\ApiController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    return new Controller\ApiController(
                        $oDbAdapter,
                        $container->get(Model\BasketTable::class),
                        $container
                    );
                },
                # Export Plugin
                Controller\ExportController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    return new Controller\ExportController(
                        $oDbAdapter,
                        $container->get(Model\BasketTable::class),
                        $container
                    );
                },
                # Search Plugin
                Controller\SearchController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    return new Controller\SearchController(
                        $oDbAdapter,
                        $container->get(Model\BasketTable::class),
                        $container
                    );
                },
                # Installer
                Controller\InstallController::class => function($container) {
                    $oDbAdapter = $container->get(AdapterInterface::class);
                    return new Controller\InstallController(
                        $oDbAdapter,
                        $container->get(Model\BasketTable::class),
                        $container
                    );
                },
            ],
        ];
    }
}
