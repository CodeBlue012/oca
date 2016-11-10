<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Core/Config/ContainerAbstract.php
 */

abstract class Brontosoftware_Magento_Core_Config_ContainerAbstract
{
    protected $_config;

    /**
     * @param Brontosoftware_Magento_Core_Config_ScopedInterface $config
     */
    public function __construct(
        Brontosoftware_Magento_Core_Config_ScopedInterface $config
    ) {
        $this->_config = $config;
    }

    /**
     * Returns an array for the setting
     *
     * @param string $path
     * @param string $scopeType
     * @param mixed $scopeId
     * @return array
     */
    protected function _getArray($path, $scopeType = 'default', $scopeId = null)
    {
        $value = $this->_config->getValue($path, $scopeType, $scopeId);
        if (empty($value)) {
            return array();
        } else if (is_string($value)) {
            return explode(',', $value);
        }
        return $value;
    }

    /**
     * Is the scope valid for the store's scope path
     *
     * @param mixed $config
     * @param mixed $store
     * @return boolean
     */
    protected function _validScope($config, $store)
    {
        return ($config->getScope() == 'default'
            || ($config->getScope() == 'websites' && $config->getScopeId() == $store->getWebsiteId())
            || ($config->getScope() == 'stores' && $config->getScopeId() == $store->getId()));
    }

    /**
     * Determines specificty for config data
     *
     * @param mixed $config
     * @param array $specificty
     * @return boolean
     */
    protected function _moreSpecific($config, $specificity)
    {
        if (array_key_exists($config->getPath(), $specificity)) {
            list($scope, $value) = $specificity[$config->getPath()];
            if ($scope == 'stores') {
                return false;
            } else if ($scope == 'default') {
                return true;
            } else {
                return $scope == 'websites' && $config->getScope() == 'stores';
            }
        }
        return true;
    }

    /**
     * Gets an XML safe edition of the objectId
     *
     * @param string $objectId
     * @return string
     */
    protected function _safeId($objectId)
    {
        if (preg_match('/^object_/', $objectId)) {
            return $objectId;
        } else {
            return 'object_' . preg_replace('|[\-\s]|', '', $objectId);
        }
    }
}
