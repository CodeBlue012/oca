<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Notification/SettingsInterface.php
 */

interface Brontosoftware_Magento_Notification_SettingsInterface extends Brontosoftware_Magento_Connector_Event_HelperInterface
{
    const XML_PATH_ENABLED = 'brontosoftware/advanced/extensions/settings/notification_enabled';
    const XML_PATH_EMAIL = 'brontosoftware/advanced/extensions/settings/notification_email';

    public function getNotificationEmail($scope = 'default', $scopeId = null);
}