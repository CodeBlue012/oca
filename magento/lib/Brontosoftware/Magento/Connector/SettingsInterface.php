<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Connector/SettingsInterface.php
 */

interface Brontosoftware_Magento_Connector_SettingsInterface
{
    const XML_PATH_SITEID = 'brontosoftware/general/settings/siteId';
    const XML_PATH_MASKID = 'brontosoftware/general/settings/maskId';
    const XML_PATH_ORDER_SERVICE = 'brontosoftware/general/features/enableOrderService';
    const XML_PATH_TEST_MODE = 'brontosoftware/advanced/extensions/testImport/enabled';
    const XML_PATH_DISABLE_FLUSH = 'brontosoftware/advanced/extensions/testImport/disableFlush';

    const XML_PATH_TOGGLE_PREFIX = 'brontosoftware/toggle/%s';

    /**
     * Gets the Bronto site hash for the registered scope
     *
     * @param string $scope
     * @param int $scopeId
     * @return string
     */
    public function getSiteId($scope = 'default', $scopeId = null);

    /**
     * Gets the Bronto maskId for authenticated imports
     *
     * @param string $scope
     * @param int $scopeId
     * @return string
     */
    public function getMaskId($scope = 'default', $scopeId = null);

    /**
     * Determines if the order service is enabled for the account
     *
     * @param string $scope
     * @param int $scopeId
     * @return bool
     */
    public function isOrderService($scope = 'default', $scopeId = null);

    /**
     * Determines if the extension has been toggle on
     *
     * @param string endpointId
     * @param string $scope
     * @param mixed $scopeId
     * @return boolean
     */
    public function isToggled($endpointId, $scope = 'default', $scopeId = null);

    /**
     * Determines if this extension is in test mode
     *
     * @param string $scope
     * @param mixed $scopeId
     * @return boolean
     */
    public function isTestMode($scope = 'default', $scopeId = null);

    /**
     * Determines if flush jobs should be skipped
     *
     * @param string $scope
     * @param mixed $scopeId
     * @return boolean
     */
    public function isFlushDisabled($scope = 'default', $scopeId = null);

    /**
     * Determines if this event is queueable
     *
     * @param mixed $scope
     * @param mixed $scopeId
     * @return boolean
     */
    public function isEventQueued($scope = 'default', $scopeId = null);
}