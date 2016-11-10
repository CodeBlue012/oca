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
 * Perficient_GoogleTagManager_Block_Googletagmanager
 *
 * @category  Perficient
 * @package   Perficient_GoogleTagManager
 * @author    Perficient <mukesh.soni@perficient.com>
 * @copyright 2016 PERFICIENT INDIA PVT LTD
 * @license   OSL http://shop.perficient.com/license-community.txt
 * @version   Release:0.0.2.1
 * @link      http://shop.perficient.com/extensions/google-tag-manager-by-perficient
 */
class Perficient_GoogleTagManager_Block_Googletagmanager extends Mage_Core_Block_Template
{

    protected function _getDataLayer()
    {
        // Initialise our data source.
        $data = array();
        $dataLayerName = Mage::helper('perficient_googletagmanager')->getDataLayerName();

        // Get transaction and visitor data, if desired.
        $data = $data + $this->_getDataLayerData();

        // Generate the data layer JavaScript.
        if (!empty($data)) {
            return "<script>" . $dataLayerName . " = [" . json_encode($data) . "];</script>\n\n";
        } else {
            return '';
        }
    }

    protected function _getDataLayerData()
    {
        $data = array();
        $orderIds = Mage::getSingleton('customer/session')->getOrderIds();
        Mage::getSingleton('customer/session')->unsOrderIds();
        $data = Mage::getModel('perficient_googletagmanager/googletagmanager')->getTransactionData($orderIds);
        $data += Mage::getModel('perficient_googletagmanager/googletagmanager')->getVisitorData();
        return $data;
    }

}