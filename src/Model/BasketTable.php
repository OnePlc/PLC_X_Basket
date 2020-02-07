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
     * @return mixed
     * @since 1.0.0
     */
    public function getSingle($id) {
        # Use core function
        return $this->getSingleEntity($id,'Basket_ID');
    }

    /**
     * Save Basket Entity
     *
     * @param Basket $oBasket
     * @return int Basket ID
     * @since 1.0.0
     */
    public function saveSingle(Basket $oBasket) {
        $aData = [
            'label' => $oBasket->label,
        ];

        $aData = $this->attachDynamicFields($aData,$oBasket);

        $id = (int) $oBasket->id;

        if ($id === 0) {
            # Add Metadata
            $aData['created_by'] = CoreController::$oSession->oUser->getID();
            $aData['created_date'] = date('Y-m-d H:i:s',time());
            $aData['modified_by'] = CoreController::$oSession->oUser->getID();
            $aData['modified_date'] = date('Y-m-d H:i:s',time());

            # Insert Basket
            $this->oTableGateway->insert($aData);

            # Return ID
            return $this->oTableGateway->lastInsertValue;
        }

        # Check if Basket Entity already exists
        try {
            $this->getSingle($id);
        } catch (\RuntimeException $e) {
            throw new \RuntimeException(sprintf(
                'Cannot update basket with identifier %d; does not exist',
                $id
            ));
        }

        # Update Metadata
        $aData['modified_by'] = CoreController::$oSession->oUser->getID();
        $aData['modified_date'] = date('Y-m-d H:i:s',time());

        # Update Basket
        $this->oTableGateway->update($aData, ['Basket_ID' => $id]);

        return $id;
    }

    /**
     * Generate new single Entity
     *
     * @return Basket
     * @since 1.0.0
     */
    public function generateNew() {
        return new Basket($this->oTableGateway->getAdapter());
    }
}