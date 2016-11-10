<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Connector/Event/HelperInterface.php
 */

interface Brontosoftware_Magento_Connector_Event_HelperInterface
{
    /**
     * Determines event enablement for the various extensions
     *
     * @param string $scope
     * @param int $scopeId
     * @return boolean
     */
    public function isEnabled($scope = 'default', $scopeId = null);
}
