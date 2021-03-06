<?php
/**
 * ExportController.php - Basket Export Controller
 *
 * Main Controller for Basket Export
 *
 * @category Controller
 * @package Basket
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.1
 */

namespace OnePlace\Basket\Controller;

use Application\Controller\CoreController;
use Application\Controller\CoreExportController;
use OnePlace\Basket\Model\BasketTable;
use Laminas\Db\Sql\Where;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\View\Model\ViewModel;


class ExportController extends CoreExportController
{
    /**
     * ApiController constructor.
     *
     * @param AdapterInterface $oDbAdapter
     * @param BasketTable $oTableGateway
     * @since 1.0.0
     */
    public function __construct(AdapterInterface $oDbAdapter,BasketTable $oTableGateway,$oServiceManager) {
        parent::__construct($oDbAdapter,$oTableGateway,$oServiceManager);
    }


    /**
     * Dump Baskets to excel file
     *
     * @return ViewModel
     * @since 1.0.1
     */
    public function dumpAction() {
        $this->layout('layout/json');

        # Use Default export function
        $aViewData = $this->exportData('Baskets','basket');

        # return data to view (popup)
        return new ViewModel($aViewData);
    }
}