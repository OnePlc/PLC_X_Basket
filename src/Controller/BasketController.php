<?php
/**
 * BasketController.php - Main Controller
 *
 * Main Controller Basket Module
 *
 * @category Controller
 * @package Basket
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

declare(strict_types=1);

namespace OnePlace\Basket\Controller;

use Application\Controller\CoreEntityController;
use Application\Model\CoreEntityModel;
use OnePlace\Basket\Model\Basket;
use OnePlace\Article\Model\ArticleTable;
use OnePlace\Article\Variant\Model\VariantTable;
use OnePlace\Basket\Model\BasketTable;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Adapter\AdapterInterface;
use OnePlace\Basket\Position\Model\PositionTable;
use OnePlace\Contact\Address\Model\AddressTable;
use OnePlace\Contact\Model\ContactTable;

class BasketController extends CoreEntityController {
    /**
     * Basket Table Object
     *
     * @since 1.0.0
     */
    protected $oTableGateway;
    protected $aPluginTables;

    /**
     * BasketController constructor.
     *
     * @param AdapterInterface $oDbAdapter
     * @param BasketTable $oTableGateway
     * @since 1.0.0
     */
    public function __construct(AdapterInterface $oDbAdapter,BasketTable $oTableGateway,$oServiceManager,$aPluginTables = []) {
        $this->oTableGateway = $oTableGateway;
        $this->sSingleForm = 'basket-single';
        parent::__construct($oDbAdapter,$oTableGateway,$oServiceManager);
        $this->aPluginTables = $aPluginTables;

        if($oTableGateway) {
            # Attach TableGateway to Entity Models
            if(!isset(CoreEntityModel::$aEntityTables[$this->sSingleForm])) {
                CoreEntityModel::$aEntityTables[$this->sSingleForm] = $oTableGateway;
            }
        }
    }

    /**
     * Basket Index
     *
     * @since 1.0.0
     * @return ViewModel - View Object with Data from Controller
     */
    public function indexAction() {
        # You can just use the default function and customize it via hooks
        # or replace the entire function if you need more customization
        return $this->generateIndexView('basket');
    }

    /**
     * Basket Add Form
     *
     * @since 1.0.0
     * @return ViewModel - View Object with Data from Controller
     */
    public function addAction() {
        /**
         * You can just use the default function and customize it via hooks
         * or replace the entire function if you need more customization
         *
         * Hooks available:
         *
         * basket-add-before (before show add form)
         * basket-add-before-save (before save)
         * basket-add-after-save (after save)
         */
        return $this->generateAddView('basket');
    }

    /**
     * Basket Edit Form
     *
     * @since 1.0.0
     * @return ViewModel - View Object with Data from Controller
     */
    public function editAction() {
        /**
         * You can just use the default function and customize it via hooks
         * or replace the entire function if you need more customization
         *
         * Hooks available:
         *
         * basket-edit-before (before show edit form)
         * basket-edit-before-save (before save)
         * basket-edit-after-save (after save)
         */
        return $this->generateEditView('basket');
    }

    /**
     * Basket View Form
     *
     * @since 1.0.0
     * @return ViewModel - View Object with Data from Controller
     */
    public function viewAction() {
        /**
         * You can just use the default function and customize it via hooks
         * or replace the entire function if you need more customization
         *
         * Hooks available:
         *
         * basket-view-before
         */
        return $this->generateViewView('basket');
    }

    /**
     * Close Basket
     *
     * @since 1.0.4
     */
    public function closeAction() {
        $this->layout('layout/json');

        $iJobID = $this->params()->fromRoute('id', 0);
        $oJob = $this->oTableGateway->getSingle($iJobID);

        # Get State Tag
        $oStateTag = CoreEntityController::$aCoreTables['core-tag']->select(['tag_key' => 'state']);
        if (count($oStateTag) > 0) {
            $oStateTag = $oStateTag->current();

            # Get Basket "done" Entity State Tag
            $oDoneState = CoreEntityController::$aCoreTables['core-entity-tag']->select([
                'entity_form_idfs' => 'basket-single',
                'tag_idfs' => $oStateTag->Tag_ID,
                'tag_key' => 'done',
            ]);

            if(count($oDoneState) > 0) {
                $oDoneState = $oDoneState->current();
                $this->oTableGateway->updateAttribute('state_idfs',$oDoneState->Entitytag_ID,'Basket_ID',$iJobID);
                $this->oTableGateway->updateAttribute('is_archived_idfs',1,'Basket_ID',$iJobID);
                $this->flashMessenger()->addSuccessMessage('Basket successfully closed');
            }
        }

        $this->flashMessenger()->addErrorMessage('Could not close basket');
        $this->redirect()->toRoute('basket',['action'=>'view','id'=>$iJobID]);
    }

    /**
     * Close Basket
     *
     * @since 1.0.4
     */
    public function orderAction() {
        $this->layout('layout/json');

        $iBasketID = $this->params()->fromRoute('id', 0);

        $oBasket = $this->oTableGateway->getSingle($iBasketID);
        if($oBasket) {
            $this->closeBasketAndCreateOrder($oBasket,$oBasket->payment_received);
            $this->flashMessenger()->addSuccessMessage('Order successfully created');
            $this->redirect()->toRoute('basket',['action'=>'view','id'=>$iBasketID]);
        } else {
            $this->flashMessenger()->addErrorMessage('Could not close basket');
            $this->redirect()->toRoute('basket',['action'=>'view','id'=>$iBasketID]);
        }
    }

    private function closeBasketAndCreateOrder($oBasket,$sPaymentReceived = '0000-00-00 00:00:00') {
        # Get State Tag
        $oStateTag = CoreEntityController::$aCoreTables['core-tag']->select(['tag_key' => 'state']);
        if (count($oStateTag) > 0) {
            $oStateTag = $oStateTag->current();

            # Get Basket "done" Entity State Tag
            $oDoneState = CoreEntityController::$aCoreTables['core-entity-tag']->select([
                'entity_form_idfs' => 'basket-single',
                'tag_idfs' => $oStateTag->Tag_ID,
                'tag_key' => 'done',
            ]);

            # only proceed of we have state tag present
            if (count($oDoneState) > 0) {
                $oDoneState = $oDoneState->current();
                $this->oTableGateway->updateAttribute('state_idfs', $oDoneState->Entitytag_ID, 'Basket_ID', $oBasket->getID());
            }
        }
        # archive basket
        $this->oTableGateway->updateAttribute('is_archived_idfs', 1, 'Basket_ID', $oBasket->getID());

        $oNewJob = $this->aPluginTables['job']->generateNew();

        # Get Job "new" Entity State Tag
        $oNewState = CoreEntityController::$aCoreTables['core-entity-tag']->select([
            'entity_form_idfs' => 'job-single',
            'tag_idfs' => $oStateTag->Tag_ID,
            'tag_key' => 'new',
        ]);
        if(count($oNewState)) {
            $oNewState = $oNewState->current();
            $aDelivery = false;
            $oDeliveryMethod = CoreEntityController::$aCoreTables['core-entity-tag']->select([
                'Entitytag_ID' => $oBasket->deliverymethod_idfs,
            ]);
            if(count($oDeliveryMethod) > 0) {
                $oDeliveryMethod = $oDeliveryMethod->current();
                $aDelivery = [
                    'id' => $oDeliveryMethod->Entitytag_ID,
                    'label' => $oDeliveryMethod->tag_value,
                    'gateway' => $oDeliveryMethod->tag_key,
                    'icon' => $oDeliveryMethod->tag_icon
                ];
            }

            $aPayment = false;
            $oPaymentMethod = CoreEntityController::$aCoreTables['core-entity-tag']->select([
                'Entitytag_ID' => $oBasket->paymentmethod_idfs,
            ]);
            if(count($oPaymentMethod) > 0) {
                $oPaymentMethod = $oPaymentMethod->current();
                $aPayment = [
                    'id' => $oPaymentMethod->Entitytag_ID,
                    'label' => $oPaymentMethod->tag_value,
                    'gateway' => $oPaymentMethod->tag_key,
                    'icon' => $oPaymentMethod->tag_icon
                ];
            }

            $aJobData = [
                'contact_idfs' => $oBasket->contact_idfs,
                'state_idfs' => $oNewState->Entitytag_ID,
                'paymentmethod_idfs' => $oBasket->paymentmethod_idfs,
                'payment_session_id' => $oBasket->payment_session_id,
                'payment_started' => $oBasket->payment_started,
                'payment_received' => $sPaymentReceived,
                'payment_id' => $oBasket->payment_id,
                'deliverymethod_idfs' => $oBasket->deliverymethod_idfs,
                'label' => 'Shop Bestellung vom '.date('d.m.Y H:i',time()),
                'date' => date('Y-m-d H:i:s',time()),
                'discount' => 0,
                'description' => 'Bestellung aus dem Shop. Kommentar des Kunden: '.$oBasket->comment,
                'created_by' => 1,
                'created_date' => date('Y-m-d H:i:s',time()),
                'modified_by' => 1,
                'modified_date' => date('Y-m-d H:i:s',time())
            ];
            $oNewJob->exchangeArray($aJobData);
            $iNewJobID = $this->aPluginTables['job']->saveSingle($oNewJob);
            $this->oTableGateway->updateAttribute('job_idfs',$iNewJobID,'Basket_ID',$oBasket->getID());

            $aPositions = $this->getBasketPositions($oBasket);
            $sPosHtml = '';
            $fTotal = 0;
            if(count($aPositions) > 0) {
                $iSortID = 0;
                foreach($aPositions as $oPos) {
                    $sHtmlLabel = '-';
                    switch($oPos->article_type) {
                        case 'article':
                            $sHtmlLabel = $oPos->oArticle->label;
                            break;
                        case 'variant':
                            $sHtmlLabel = $oPos->oArticle->label.': '.$oPos->oVariant->label;
                            break;
                        case 'event':
                            $sHtmlLabel = $oPos->oEvent->label.': '.$oPos->oVariant->label;
                            break;
                        default:
                            break;
                    }
                    $sPosHtml .= '<tr>';
                    $sPosHtml .= '<td>'.$sHtmlLabel.'</td>';
                    $sPosHtml .= '<td>'.$oPos->amount.'</td>';
                    $sPosHtml .= '<td>'.number_format((float)$oPos->price,2,',','.').' €</td>';
                    $sPosHtml .= '<td>'.$oPos->comment.'</td></tr>';

                    $this->aPluginTables['job-position']->insert([
                        'job_idfs' => $iNewJobID,
                        'article_idfs' => $oPos->article_idfs,
                        'ref_idfs' => $oPos->ref_idfs,
                        'ref_type' => $oPos->ref_type,
                        'type' => $oPos->article_type,
                        'sort_id' => $iSortID,
                        'amount' => $oPos->amount,
                        'price' => $oPos->price,
                        'discount' => 0,
                        'discount_type' => 'percent',
                        'description' => $oPos->comment
                    ]);
                    $fTotal+=($oPos->amount*$oPos->price);
                    $iSortID++;
                }
            }
            if($fTotal <= 100 && $aDelivery['gateway'] == 'mail') {
                $this->aPluginTables['job-position']->insert([
                    'job_idfs' => $iNewJobID,
                    'article_idfs' => 0,
                    'ref_idfs' => 0,
                    'ref_type' => 'none',
                    'type' => 'custom',
                    'sort_id' => $iSortID,
                    'amount' => 1,
                    'price' => 2.5,
                    'discount' => 0,
                    'discount_type' => 'percent',
                    'description' => 'Lieferkosten Postversand unter 100 €'
                ]);
            }

            $oContactTbl = CoreEntityController::$oServiceManager->get(ContactTable::class);
            $oAddressTbl = CoreEntityController::$oServiceManager->get(AddressTable::class);
            try {
                $oContact = $oContactTbl->getSingle($oBasket->contact_idfs);
            } catch (\RuntimeException $e) {

            }

            if(isset($oContact)) {
                $oAddress = $oAddressTbl->getSingle($oContact->getID(),'contact_idfs');
                $oContact->address = $oAddress;

                $sEmail = $aPayment['gateway'];
                if($aDelivery['gateway'] == 'pickup') {
                    $sEmail = 'instore';
                }

                $this->sendEmail('email/shop/'.$sEmail,[
                    'sInstallInfo' => CoreEntityController::$aGlobalSettings['shop-email-subject-receipt'],
                    'sContactName' => $oContact->getLabel(),
                    'sContactEmail' => $oContact->getTextField('email_private'),
                    'sContactPhone' => $oContact->getTextField('phone_private'),
                    'sContactAddr' => $oContact->address->street,
                    'sOrderComment' => $oBasket->comment,
                    'sPaymentMethod' => $aPayment['label'],
                    'sDeliveryMethod' => $aDelivery['label'],
                    'oOrderPositions' => $sPosHtml,
                    'fOrderTotal' => $fTotal,
                    'sLogoPath' => 'https://schwitzers.onep.lc/img/logo.png',
                ],$oContact->email_private,$oContact->getLabel(),CoreEntityController::$aGlobalSettings['shop-email-subject-receipt']);
            }


        } else {
            // could not find state "new" tag for job
        }
    }

    private function getBasketPositions($oBasketExists) {
        # we have a basket - lets check for positions
        $aPositions = [];
        $oPosTbl = CoreEntityController::$oServiceManager->get(PositionTable::class);
        $oArticleTbl = CoreEntityController::$oServiceManager->get(ArticleTable::class);
        $oVariantTbl = CoreEntityController::$oServiceManager->get(VariantTable::class);

        try {
            $oEventTbl = CoreEntityController::$oServiceManager->get(\OnePlace\Event\Model\EventTable::class);
        } catch(\RuntimeException $e) {
            # event plugin not present
        }

        # attach positions
        $oBasketPositions = $oPosTbl->fetchAll(false,['basket_idfs' => $oBasketExists->getID()]);
        if(count($oBasketPositions) > 0) {
            foreach($oBasketPositions as $oPos) {
                switch($oPos->article_type) {
                    case 'variant':
                        $oPos->oVariant = $oVariantTbl->getSingle($oPos->article_idfs);
                        $oPos->oArticle = $oArticleTbl->getSingle($oPos->oVariant->article_idfs);
                        $oPos->oArticle->featured_image = '/data/article/'.$oPos->oArticle->getID().'/'.$oPos->oArticle->featured_image;
                        # check for custom price (used for free amount coupons)
                        if($oPos->price != 0) {
                            $oPos->oVariant->price = $oPos->price;
                        }
                        # event plugin
                        if($oPos->ref_idfs != 0) {
                            switch($oPos->ref_type) {
                                case 'event':
                                    if(isset($oEventTbl)) {
                                        $oPos->article_type = 'event';
                                        $oPos->oEvent = $oEventTbl->getSingle($oPos->ref_idfs);
                                        # Event Rerun Plugin Start
                                        if($oPos->oEvent->root_event_idfs != 0) {
                                            $oRoot = $oEventTbl->getSingle($oPos->oEvent->root_event_idfs);
                                            $oPos->oEvent->label = $oRoot->label;
                                            $oPos->oEvent->excerpt = $oRoot->excerpt;
                                            $oPos->oEvent->featured_image = $oRoot->featured_image;
                                            $oPos->oEvent->description = $oRoot->description;
                                            $oPos->oEvent->featured_image = '/data/event/'.$oRoot->getID().'/'.$oPos->oEvent->featured_image;
                                        } else {
                                            $oPos->oEvent->featured_image = '/data/event/'.$oPos->oEvent->getID().'/'.$oPos->oEvent->featured_image;
                                        }
                                    }
                                    break;
                                default:
                                    break;
                            }
                        }
                        break;
                    default:
                        break;
                }
                $aPositions[] = $oPos;
            }
        }

        return $aPositions;
    }
}
