<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Connector/Discovery/TransformEventInterface.php
 */

interface Brontosoftware_Magento_Connector_Discovery_TransformEventInterface
{
    /**
     * This extension will transform queued events into Sarlacc data
     *
     * @param mixed $observer
     * @return void
     */
    public function transformEvent($observer);
}