<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Coupon/ManagerInterface.php
 */

interface Brontosoftware_Magento_Coupon_ManagerInterface
{
    const XML_PATH_OBJECT_PATH = 'brontosoftware/coupon/objects/generator/%';

    /**
     * Gets all of the generators created in Connector
     *
     * @param Brontosoftware_Magento_Connector_RegistrationInterface $registration
     * @return array
     */
    public function getAll(Brontosoftware_Magento_Connector_RegistrationInterface $registration);

    /**
     * Gets a single generator created in Connector
     *
     * @param string $generatorId
     * @param boolean $force
     * @return array
     */
    public function getById($generatorId, $force = false);

    /**
     * Saves a single generator
     *
     * @param string $generatorId
     * @param array $generator
     * @param Brontosoftware_Magento_Connector_RegistrationInterface $registration
     * @return array
     */
    public function save($generatorId, $generator, Brontosoftware_Magento_Connector_RegistrationInterface $registration);

    /**
     * Increments the replenish count for a generator
     * Returns a collection of coupons that were generated
     *
     * @param mixed $generator
     * @param int $amount
     * @return array
     */
    public function acquireCoupons($generator, $amount = null);

    /**
     * Generates one unique coupon code from a specific generator
     *
     * @param string $generatorId
     * @return string
     */
    public function acquireCoupon($generatorId);

    /**
     * Gets all of the replenishable pools from the platform
     *
     * @param Brontosoftware_Magento_Connector_RegistrationInterface $registration
     * @return array
     */
    public function getReplenishablePoolIds(Brontosoftware_Magento_Connector_RegistrationInterface $registration);
}
