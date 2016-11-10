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
 * Perficient_GoogleTagManager_Helper_Data
 *
 * @category  Perficient
 * @package   Perficient_GoogleTagManager
 * @author    Perficient <mukesh.soni@perficient.com>
 * @copyright 2016 PERFICIENT INDIA PVT LTD
 * @license   OSL http://shop.perficient.com/license-community.txt
 * @version   Release:0.0.2.1
 * @link      http://shop.perficient.com/extensions/google-tag-manager-by-perficient
 */
class Perficient_GoogleTagManager_Helper_Data extends Mage_Core_Helper_Abstract
{

    public $first_visit;
    public $previous_visit;
    public $current_visit;
    public $times_visited;
    public $unique_identifier;
    public $campaign_source;
    public $campaign_name;
    public $campaign_medium;
    public $cookie_set_time;
    public $session_number;
    public $campaign_term;
    public $campaign_content;
    public $campaign_number;
    public $pages_viewed;
    public $utmb_current_visit;

    const XML_PATH_ENABLED = 'perficient_googletagmanager/general/is_enabled';
    const XML_PATH_CONTAINER = 'perficient_googletagmanager/general/containerid';
    const XML_PATH_DATALAYER_NAME = 'perficient_googletagmanager/general/datalayer_name';
    CONST XML_ENABLE_ANALYTICS = 'perficient_googletagmanager/general/enable_orderanalytics';
    CONST XML_ADDITIONAL_COOKIES = 'perficient_googletagmanager/general/additional_cookies';

    /**
     * Determine if GTM is ready to use.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return Mage::getStoreConfig(self::XML_PATH_CONTAINER) && Mage::getStoreConfigFlag(self::XML_PATH_ENABLED);
    }

    /**
     * Get the GTM container ID.
     *
     * @return string
     */
    public function getContainerId()
    {
        return Mage::getStoreConfig(self::XML_PATH_CONTAINER);
    }

    /**
     * Get the name of Data Layer that contains the data.
     *
     * @return string
     */
    public function getDataLayerName()
    {
        return Mage::getStoreConfig(self::XML_PATH_DATALAYER_NAME);
    }

    /**
     * Check If Order Analytics Module is enabled
     * @return boolean
     */
    public function isOrderAnalyticsEnabled()
    {
        if (!Mage::getStoreConfigFlag(self::XML_ENABLE_ANALYTICS)) {
            return false;
        }
        return true;
    }

    /**
     * 
     * Grab Additional Cookies
     */
    public function getAdditionalCookies()
    {
        $additionalCookies = array();
        $aCookies = Mage::getStoreConfig(self::XML_ADDITIONAL_COOKIES);
        if ($aCookies != '') {
            $cookiesData = preg_split('[\|]', $aCookies);
            if (count($cookiesData) > 0) {
                foreach ($cookiesData as $key => $value) {
                    list($label, $cookieName) = explode('=', $value);
                    $additionalCookies[$cookieName] = $label;
                }
            }
        }
        return $additionalCookies;
    }

    /**
     * 
     * Grab Additional Cookies Values
     */
    public function getAdditionalCookiesValues()
    {
        $additionalCookies = $this->getAdditionalCookies();
        $additionalCookiesValue = array();

        if (count($additionalCookies) > 0) {
            foreach ($additionalCookies as $ck => $lbl) {
                $additionalCookiesValue[$lbl] = Mage::getModel('core/cookie')->get($ck);
            }
        }
        return $additionalCookiesValue;
    }

    /**
     * 
     * Parse GA Cookies
     */
    public function parseUTMACookie($__utma = '')
    {
		$domain_hash ="";
		$unique_identifier ="";
		$time_initial_visit ="";
		$time_beginning_previous_visit ="";
        $time_beginning_current_visit ="";
		$no_of_sessions ="";
        // Parse the __utma Cookie
        if(!empty($__utma))
        list($domain_hash, $unique_identifier, $time_initial_visit, $time_beginning_previous_visit,
        $time_beginning_current_visit, $no_of_sessions) = preg_split('[\.]', $__utma);
        $this->unique_identifier = $unique_identifier;
        $this->first_visit = date("M d Y - H:i:s", $time_initial_visit);
        $this->previous_visit = date("M d Y - H:i:s", $time_beginning_previous_visit);
        $this->current_visit = date("M d Y - H:i:s", $time_beginning_current_visit);
        $this->times_visited = $no_of_sessions;
        return $this;
    }

    /**
     * 
     * Parse UTMZ GA Cookies
     */
    public function parseUTMZCookie($__utmz = '')
    {
        // Parse the __utmz Cookie
        if(!empty($__utmz))
        list($domain_hash, $timestamp, $this->session_number, $this->campaign_number, $campaign_data) = 
            preg_split('[\.]', $__utmz);

        $this->cookie_set_time = date("M d Y - H:i:s", $timestamp);

        // Parse the campaign data
        $campaign_data = parse_str(strtr($campaign_data, "|", "&"), $output);

        if (isset($output['utmcsr'])) {
            $this->campaign_source = $output['utmcsr'];
        }
        if (isset($output['utmccn'])) {
            $this->campaign_name = $output['utmccn'];
        }
        if (isset($output['utmcmd'])) {
            $this->campaign_medium = $output['utmcmd'];
        }
        if (isset($output['utmctr']))
            $this->campaign_term = $output['utmctr'];
        if (isset($output['utmcct']))
            $this->campaign_content = $output['utmcct'];

        if (isset($utmgclid)) {
            $this->campaign_source = "google";
            $this->campaign_name = "";
            $this->campaign_medium = "cpc";
            $this->campaign_content = "";
            $this->campaign_term = $output['utmctr'];
        }
        return $this;
    }

    /**
     * 
     * Parse UTMB GA Cookies
     */
    public function parseUTMBCookie($__utmb = '')
    {
        // Parse the __utmb Cookie
        if(!empty($__utmb))
        list($domain_hash, $this->pages_viewed, $garbage, $time_beginning_current_session) = 
            preg_split('[\.]', $__utmb);
        $this->utmb_current_visit = date("M d Y - H:i:s", $time_beginning_current_session);
        return $this;
    }


    /**
     * Get Category Path
     * 
     * @param array $catIds optional
     *
     * @return string
     */
    public function getCategoryPath($catIds = null)
    {
        Mage::unregister('category_name');
        $catPath  = '';
        $category = Mage::registry('current_category');
        if (!isset($category)) {
            if ($catIds) {
                $category = Mage::getModel(
                    'catalog/category'
                )->load($catIds[0]);
            }
        }
        if (isset($category)) {
            Mage::register('category_name', $category->getName());
            $coll    = $category->getResourceCollection();
            $pathIds = $category->getPathIds();
            $coll->addAttributeToSelect('name');
            $coll->addAttributeToFilter('entity_id', array('in' => $pathIds));
            $catPath = '';
            foreach ($coll as $cat) {
                $catPath .= $cat->getName().'/';
            }
        }

        return $catPath;
    }//end getCategoryPath()
    

}