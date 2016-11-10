<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Redemption/Settings.php
 */

class Brontosoftware_Magento_Redemption_Settings extends Brontosoftware_Magento_Integration_CouponSettings implements Brontosoftware_Magento_Redemption_SettingsInterface
{
    /**
     * @see parent
     */
    public function isToggled($scope = 'default', $scopeId = null)
    {
        return $this->_config->getValue(self::XML_PATH_API_TOGGLE, $scope, $scopeId) == 'api';
    }

    /**
     * @see parent
     */
    public function isEnabled($scope = 'default', $scopeId = null)
    {
        return (
            $this->isToggled($scope, $scopeId) &&
            $this->_config->isSetFlag(self::XML_PATH_COUPON_ENABLED, $scope, $scopeId)
        );
    }

    /**
     * @see parent
     */
    public function isCouponEnabled($scope = 'default', $scopeId = null)
    {
        return (
            !$this->isToggled($scope, $scopeId) &&
            parent::isCouponEnabled($scope, $scopeId)
        );
    }
}