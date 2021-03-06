<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Balance/Settings.php
 */

class Brontosoftware_Magento_Balance_Settings implements Brontosoftware_Magento_Contact_AttributeSettingsInterface
{
    public static $_fields = array(
        'store_credit_currency' => array('name' => 'Store Credit', 'type' => 'float'),
        'store_credit' => array('name' => 'Store Credit Formatted', 'type' => 'text')
    );

    public static $_formatOptions = array(
        'store_credit_currency' => array('display' => 1, 'precision' => 2),
        'store_credit' => array('display' => 2, 'precision' => 2)
    );

    protected $_storeManager;
    protected $_credits;

    /**
     * @param Brontosoftware_Magento_Core_Store_ManagerInterface $storeManager
     * @param Brontosoftware_Magento_Balance_ManagerInterface $credits
     */
    public function __construct(
        Brontosoftware_Magento_Core_Store_ManagerInterface $storeManager,
        Brontosoftware_Magento_Balance_ManagerInterface $credits
    ) {
        $this->_storeManager = $storeManager;
        $this->_credits = $credits;
    }

    /**
     * @see parent
     */
    public function getFields()
    {
        return self::$_fields;
    }

    /**
     * @see parent
     */
    public function getExtra($contact, $storeId = null)
    {
        $store = $this->_storeManager->getStore($storeId);
        $currency = $store->getWebsite()->getBaseCurrency();
        $credit = $this->_credits->getByCustomer($contact->getId(), $store->getWebsiteId());
        $return = array();
        foreach ($this->getFields() as $fieldId => $fieldLabel) {
            $return[$fieldId] = '';
            if ($credit) {
                $options = self::$_formatOptions[$fieldId];
                $return[$fieldId] = $currency->formatTxt($credit->getAmount(), $options);
            }
        }
        return $return;
    }
}
