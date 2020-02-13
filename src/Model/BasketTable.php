<?php
/**
 * BasketTable.php - Basket Table
 *
 * Table Model for Basket
 *
 * @category Model
 * @package Basket
 * @author Verein onePlace
 * @copyright (C) 2020 Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

namespace OnePlace\Basket\Model;

use Application\Controller\CoreController;
use Application\Model\CoreEntityTable;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Paginator\Paginator;
use Laminas\Paginator\Adapter\DbSelect;

class BasketTable extends CoreEntityTable {

    /**
     * BasketTable constructor.
     *
     * @param TableGateway $tableGateway
     * @since 1.0.0
     */
    public function __construct(TableGateway $tableGateway) {
        parent::__construct($tableGateway);

        # Set Single Form Name
        $this->sSingleForm = 'basket-single';
    }

    /**
     * Get Basket Entity
     *
     * @param int $id
     * @param string $sKey
     * @return mixed
     * @since 1.0.0
     */
    public function getSingle($id,$sKey = 'Basket_ID') {
        # Use core function
        return $this->getSingleEntity($id,$sKey);
    }

    /**
     * Save Basket Entity
     *
     * @param Basket $oBasket
     * @return int Basket ID
     * @since 1.0.0
     */
    public function saveSingle(Basket $oBasket) {
        $aDefaultData = [
            'label' => $oBasket->label,
        ];

        return $this->saveSingleEntity($oBasket,'Basket_ID',$aDefaultData);
    }

    /**
     * Generate new single Entity
     *
     * @return Basket
     * @since 1.0.1
     */
    public function generateNew() {
        return new Basket($this->oTableGateway->getAdapter());
    }
}