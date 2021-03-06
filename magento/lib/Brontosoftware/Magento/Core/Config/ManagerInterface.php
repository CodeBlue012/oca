<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Core/Config/ManagerInterface.php
 */

interface Brontosoftware_Magento_Core_Config_ManagerInterface
{
    /**
     * Saves a single entry on the platform
     *
     * @param string $path
     * @param mixed $value
     * @param string $scopeName
     * @param mixed $scopeId
     * @return void
     */
    public function save($path, $value, $scopeName, $scopeId);

    /**
     * Reinits the config cache
     *
     * @return void
     */
    public function reinit();

    /**
     * Deletes all of the stored settings by scope parent and path prefix
     *
     * @param string $path
     * @param string $scopeName
     * @param mixed $scopeId
     * @return void
     */
    public function deleteAll($path, $scopeName, $scopeId);
}
