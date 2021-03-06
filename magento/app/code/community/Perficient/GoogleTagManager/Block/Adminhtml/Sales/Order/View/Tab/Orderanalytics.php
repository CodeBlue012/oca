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
 * Perficient_GoogleTagManager_Block_Adminhtml_Sales_Order_View_Tab_Orderanalytics
 *
 * @category  Perficient
 * @package   Perficient_GoogleTagManager
 * @author    Perficient <mukesh.soni@perficient.com>
 * @copyright 2016 PERFICIENT INDIA PVT LTD
 * @license   OSL http://shop.perficient.com/license-community.txt
 * @version   Release:0.0.2.1
 * @link      http://shop.perficient.com/extensions/google-tag-manager-by-perficient
 */
class Perficient_GoogleTagManager_Block_Adminhtml_Sales_Order_View_Tab_Orderanalytics 
extends Mage_Adminhtml_Block_Sales_Order_Abstract implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    private $_helper;

    /**
     * Construct
     * @see Mage_Core_Block_Template::_construct()
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('perficient/googletagmanager/orderanalytics.phtml');
        $this->_helper = Mage::helper('perficient_googletagmanager');
    }

    /**
     * Return Tab label
     * @see Mage_Adminhtml_Block_Widget_Tab_Interface::getTabLabel()
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('perficient_googletagmanager')->__('Order Analytics');
    }

    /**
     * Return Tab Title
     * 
     * @see Mage_Adminhtml_Block_Widget_Tab_Interface::getTabTitle()
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('perficient_googletagmanager')->__('Order Analytics');
    }

    /**
     * Can This Tab be shown in tabs
     * 
     * @see Mage_Adminhtml_Block_Widget_Tab_Interface::canShowTab()
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Is this Tab hidden
     * 
     * @see Mage_Adminhtml_Block_Widget_Tab_Interface::isHidden()
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Retrieve order model instance
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('current_order');
    }

    /**
     * 
     * @return Perficient_Orderanalytics_Model_Orderanalytics
     */
    public function getCurrentOrderAnalytics()
    {
        if (!Mage::registry('current_order_analytics')) {
            if ($this->getOrderAnalytics() !== false) {
                Mage::register('current_order_analytics', $this->getOrderAnalytics());
            }
        }
        return Mage::registry('current_order_analytics');
    }

    /**
     * 
     * Retrieve Order Analytics
     */
    public function getOrderAnalytics()
    {
        return Mage::getModel('perficient_googletagmanager/orderanalytics')
                        ->getOrderAnalytics($this->getOrder()->getId());
    }

    /**
     * Retrieve Unique Identifier from
     * __utma GA cookie
     */
    public function getUniqueIdentifier()
    {
        if ($this->getOrderAnalytics() === false)
            return;
        return $this->_helper->parseUTMACookie(
            $this->getCurrentOrderAnalytics()
            ->getData('utma')
        )
            ->unique_identifier;
    }

    /**
     * Retrieve First Visit
     * from __utma cookie
     */
    public function getFirstVisit()
    {
        if ($this->getOrderAnalytics() === false)
            return;
        return $this->_helper->parseUTMACookie(
            $this->getCurrentOrderAnalytics()
            ->getData('utma')
        )
            ->first_visit;
    }

    /**
     * 
     * Retrieve Previous Visit
     */
    public function getPreviousVisit()
    {
        if ($this->getOrderAnalytics() === false)
            return;
        return $this->_helper->parseUTMACookie(
            $this->getCurrentOrderAnalytics()
            ->getData('utma')
        )
            ->previous_visit;
    }

    /**
     * 
     * Retrieve Current Visit
     */
    public function getCurrentVisit()
    {
        if ($this->getOrderAnalytics() === false)
            return;
        return $this->_helper->parseUTMACookie(
            $this->getCurrentOrderAnalytics()
            ->getData('utma')
        )
            ->current_visit;
    }

    /**
     * 
     * Retrieve No Of Sessions
     */
    public function getNoOfSessions()
    {
        if ($this->getOrderAnalytics() === false)
            return;
        return $this->_helper->parseUTMACookie(
            $this->getCurrentOrderAnalytics()
            ->getData('utma')
        )
            ->times_visited;
    }

    /**
     * 
     * Retrieve Cookie Time
     */
    public function getCookieTime()
    {
        if ($this->getOrderAnalytics() === false)
            return;
        return $this->_helper->parseUTMZCookie(
            $this->getCurrentOrderAnalytics()
            ->getData('utmz')
        )
            ->cookie_set_time;
    }

    /**
     * 
     * Retrieve No Of Sessions
     */
    public function getSessionNumber()
    {
        if ($this->getOrderAnalytics() === false)
            return;
        return $this->_helper->parseUTMZCookie(
            $this->getCurrentOrderAnalytics()
            ->getData('utmz')
        )
            ->session_number;
    }

    /**
     * 
     * Enter description here ...
     */
    public function getCampaignNumber()
    {
        if ($this->getOrderAnalytics() === false)
            return;
        return $this->_helper->parseUTMZCookie(
            $this->getCurrentOrderAnalytics()
            ->getData('utmz')
        )
            ->campaign_number;
    }

    /**
     * 
     * Enter description here ...
     */
    public function getCampaignSource()
    {
        if ($this->getOrderAnalytics() === false)
            return;
        return $this->_helper->parseUTMZCookie(
            $this->getCurrentOrderAnalytics()
            ->getData('utmz')
        )
            ->campaign_source;
    }

    /**
     * 
     * Enter description here ...
     */
    public function getCampaignName()
    {
        if ($this->getOrderAnalytics() === false)
            return;
        return $this->_helper->parseUTMZCookie(
            $this->getCurrentOrderAnalytics()
            ->getData('utmz')
        )
            ->campaign_name;
    }

    /**
     * 
     * Enter description here ...
     */
    public function getCampaignMedium()
    {
        if ($this->getOrderAnalytics() === false)
            return;
        return $this->_helper->parseUTMZCookie(
            $this->getCurrentOrderAnalytics()
            ->getData('utmz')
        )
            ->campaign_medium;
    }

    /**
     * 
     * Enter description here ...
     */
    public function getCampaignKeyword()
    {
        if ($this->getOrderAnalytics() === false)
            return;
        return $this->_helper->parseUTMZCookie(
            $this->getCurrentOrderAnalytics()
            ->getData('utmz')
        )
            ->campaign_term;
    }

    /**
     * 
     * Enter description here ...
     */
    public function getPageViewed()
    {
        if ($this->getOrderAnalytics() === false)
            return;
        return $this->_helper->parseUTMBCookie(
            $this->getCurrentOrderAnalytics()
            ->getData('utmb')
        )
            ->pages_viewed;
    }

    /**
     * 
     * Enter description here ...
     */
    public function getCurrentSessionTime()
    {
        if ($this->getOrderAnalytics() === false)
            return;
        return $this->_helper->parseUTMBCookie(
            $this->getCurrentOrderAnalytics()
            ->getData('utmb')
        )
            ->utmb_current_visit;
    }

    public function getCustomValues()
    {
        if ($this->getOrderAnalytics() === false)
            return;
        return unserialize(
            $this->getCurrentOrderAnalytics()
                 ->getData('additionalcookies')
        );
    }

}