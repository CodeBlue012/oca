<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Core/EncryptorInterface.php
 */

interface Brontosoftware_Magento_Core_EncryptorInterface
{
    /**
     * Forwards implementation calls to the platform
     *
     * @param string $message
     * @return string
     */
    public function encrypt($message);

    /**
     * Forwards implementation calls to the platform
     *
     * @param string $message
     * @return string
     */
    public function decrypt($message);
}
