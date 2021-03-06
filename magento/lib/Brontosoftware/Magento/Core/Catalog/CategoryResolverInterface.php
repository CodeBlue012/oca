<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Core/Catalog/CategoryResolverInterface.php
 */

interface Brontosoftware_Magento_Core_Catalog_CategoryResolverInterface
{
    /**
     * Returns a string that representing product categories
     *
     * @return string
     */
    public function resolve($branches);
}
