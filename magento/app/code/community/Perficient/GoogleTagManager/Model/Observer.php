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
 * Perficient_GoogleTagManager_Model_Observer
 *
 * @category  Perficient
 * @package   Perficient_GoogleTagManager
 * @author    Perficient <mukesh.soni@perficient.com>
 * @copyright 2016 PERFICIENT INDIA PVT LTD
 * @license   OSL http://shop.perficient.com/license-community.txt
 * @version   Release:0.0.2.1
 * @link      http://shop.perficient.com/extensions/google-tag-manager-by-perficient
 */
class Perficient_GoogleTagManager_Model_Observer
{

    public function setOrderData(Varien_Event_Observer $observer)
    {
        $orderIds = $observer->getData('order_ids');
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        } else {
            Mage::getSingleton('customer/session')->setOrderIds($orderIds);
        }
    }

    /**
     * 
     * Collect Google Analytics Data for the Order
     * and Save to Order analytics Table
     * @param Object $observer
     */
    public function collectData($observer)
    {
        if (!Mage::helper('perficient_googletagmanager')->isOrderAnalyticsEnabled()) {
            return;
        }

        try {
            $order = $observer->getEvent()->getOrder();
            $incrementId = $order->getIncrementId();
            $orderId = $order->getId();
            if (0 < $orderId) {
                Mage::log(print_r(Mage::getModel('core/cookie'), 1), 1, 'cookie1.txt', 1);
                Mage::log(print_r($_COOKIE, 1), 1, 'cookie2.txt', 1);
                $orderanalyticsModel = Mage::getModel('perficient_googletagmanager/orderanalytics');
                $orderanalyticsModel->setAnalyticsId()
                        ->setOrderId($orderId)
                        ->setOrderIncrementId($incrementId)
                        ->setCustomerEmail($order->getCustomerEmail())
                        ->setCustomerName($order->getCustomerFirstname() . ' ' . $order->getCustomerLastname())
                        ->setRemoteIp($order->getRemoteIp())
                        ->setUtma(Mage::getModel('core/cookie')->get('__utma'))
                        ->setUtmb(Mage::getModel('core/cookie')->get('__utmb'))
                        ->setUtmc(Mage::getModel('core/cookie')->get('__utmc'))
                        ->setUtmz(Mage::getModel('core/cookie')->get('__utmz'))
                        ->setUtmv(Mage::getModel('core/cookie')->get('__utmv'))
                        ->setAdditionalcookies(
                            serialize(Mage::helper('perficient_googletagmanager')->getAdditionalCookiesValues())
                        )
                        ->save();
            }
        } catch (Exception $e) {
            //Log the exception in query if any
            Mage::log('GA_ORDERANALYTICS', $e);
        }
        return;
    }

}
