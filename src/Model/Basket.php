<?php
/**
 * Basket.php - Basket Entity
 *
 * Entity Model for Basket
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

use Application\Model\CoreEntityModel;

class Basket extends CoreEntityModel {
    public $label;
    public $shop_session_id;

    /**
     * Basket constructor.
     *
     * @param AdapterInterface $oDbAdapter
     * @since 1.0.0
     */
    public function __construct($oDbAdapter) {
        parent::__construct($oDbAdapter);

        # Set Single Form Name
        $this->sSingleForm = 'basket-single';

        # Attach Dynamic Fields to Entity Model
        $this->attachDynamicFields();
    }

    /**
     * Set Entity Data based on Data given
     *
     * @param array $aData
     * @since 1.0.0
     */
    public function exchangeArray(array $aData) {
        $this->id = !empty($aData['Basket_ID']) ? $aData['Basket_ID'] : 0;
        $this->label = !empty($aData['label']) ? $aData['label'] : '';
        $this->shop_session_id = !empty($aData['shop_session_id']) ? $aData['shop_session_id'] : '';

        $this->updateDynamicFields($aData);
    }
}