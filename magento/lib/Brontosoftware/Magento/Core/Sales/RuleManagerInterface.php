<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Core/Sales/RuleManagerInterface.php
 */

interface Brontosoftware_Magento_Core_Sales_RuleManagerInterface
{
    /**
     * Gets a collection of rules determined by the source request
     *
     * @param Brontosoftware_Magento_Connector_Discovery_Source $source
     * @param boolean $onlyPools
     * @return Iterator
     */
    public function getBySource(Brontosoftware_Magento_Connector_Discovery_Source $source, $onlyPools = true);

    /**
     * Gets a coupon rule by its ID
     *
     * @param mixed $ruleId
     * @return mixed
     */
    public function getById($ruleId);

    /**
     * Provided generator data, determine if the associated pool can
     * be replenished
     *
     * @param array $data
     * @return boolean
     */
    public function isReplenishable($data);

    /**
     * Uses a generation data defined in Connector to generate some coupons
     *
     * @param array $data ['rule_id' => 'n', ... ]
     * @return array
     */
    public function acquireCoupons($data);

    /**
     * Stream all of the unused coupons for a pool
     *
     * @param mixed $ruleId
     * @param mixed $startTime
     * @param mixed $endTime
     * @param mixed $codePrefix
     * @param mixed $codeSuffix
     * @param int $limit
     * @param int $offset
     * @return Iterator
     */
    public function unusedCoupons($ruleId, $startTime = null, $endTime = null, $codePrefix = null, $codeSuffix = null, $limit = 20, $offset = 0);
}