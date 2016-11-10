<?php 
/**
 * PERFICIENT INDIA PVT LTD.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://shop.perficient.com/license-community.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * This package designed for Magento COMMUNITY edition
 * =================================================================
 * Perficient does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Perficient does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * PHP version 5.x
 *
 * @category  Perficient
 * @package   Perficient_GoogleTagManager
 * @author    Perficient <mukesh.soni@perficient.com>
 * @copyright 2016 PERFICIENT INDIA PVT LTD
 * @license   OSL http://shop.perficient.com/license-community.txt
 * @version   GIT:0.0.2.1
 * @link      http://shop.perficient.com/extensions/google-tag-manager-by-perficient
 */



/**
 * Perficient_GoogleTagManager_Model_Resource_Orderanalytics
 *
 * @category  Perficient
 * @package   Perficient_GoogleTagManager
 * @author    Perficient <mukesh.soni@perficient.com>
 * @copyright 2016 PERFICIENT INDIA PVT LTD
 * @license   OSL http://shop.perficient.com/license-community.txt
 * @version   Release:0.0.2.1
 * @link      http://shop.perficient.com/extensions/google-tag-manager-by-perficient
 */
class Perficient_GoogleTagManager_Model_Resource_Orderanalytics extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * Constructor
     * @see Varien_Object::_construct()
     */
    protected function _construct()
    {
        $this->_init('perficient_googletagmanager/sales', 'analytics_id');
    }

    /**
     * 
     * Get Order Analytics
     * @param Int $orderId
     * @return Array OrderAnalytics
     */
    public function getOrderAnalytics($orderId = null)
    {
        if (!is_null($orderId) && 0 < $orderId) {
            $adapter = $this->_getReadAdapter();
            $select = $adapter->select()
                    ->from($this->getTable('perficient_googletagmanager/sales'))
                    ->where('order_id=:order_id');
            $data = $adapter->fetchRow($select, array('order_id' => $orderId));
            if ($data && $data['order_id'] > 0) {
                $rowObj = new Varien_Object();
                $rowObj->setData($data);
                return $rowObj;
            }
        }
        return false;
    }

}