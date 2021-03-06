<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Integration/CartSettings.php
 */

class Brontosoftware_Magento_Integration_CartSettings extends Brontosoftware_Magento_Core_Config_ContainerAbstract implements Brontosoftware_Magento_Integration_CartSettingsInterface
{
    const BRONTO_CR_EMAIL = '__btr_em';
    const REDIRECT_PATH = 'brontosoftware/redirect/index';

    protected $_cookies;
    protected $_encrypt;
    protected $_urls;

    /**
     * @param Brontosoftware_Magento_Core_EncryptorInterface $encrypt
     * @param Brontosoftware_Magento_Core_Cookie_ReaderInterface $cookies
     * @param Brontosoftware_Magento_Core_Config_ScopedInterface $config
     * @param Brontosoftware_Magento_Core_Store_UrlManagerInterface $urls
     */
    public function __construct(
        Brontosoftware_Magento_Core_EncryptorInterface $encrypt,
        Brontosoftware_Magento_Core_Cookie_ReaderInterface $cookies,
        Brontosoftware_Magento_Core_Config_ScopedInterface $config,
        Brontosoftware_Magento_Core_Store_UrlManagerInterface $urls
    ) {
        parent::__construct($config);
        $this->_cookies = $cookies;
        $this->_encrypt = $encrypt;
        $this->_urls = $urls;
    }

    /**
     * @see parent
     */
    public function isCartRecoveryEnabled($scopeType = 'default', $scopeId = null)
    {
        return $this->_config->isSetFlag(self::XML_PATH_RECOVERY_ENABLED, $scopeType, $scopeId);
    }

    /**
     * @see parent
     */
    public function getCartRecoveryEmbedCode($scopeType = 'default', $scopeId = null)
    {
        return $this->_config->getValue(self::XML_PATH_RECOVERY_EMBED, $scopeType, $scopeId);
    }

    /**
     * @see parent
     */
    public function getCartRecoveryEmail($quote)
    {
        if ($quote->getCustomerEmail()) {
            return $quote->getCustomerEmail();
        } else {
            // Attempt to read the bronto email capture cookie
            $encoded = $this->_cookies->getCookie(self::BRONTO_CR_EMAIL, '');
            if (empty($encoded)) {
                return null;
            }
            return base64_decode(str_pad(strtr($encoded, '-_', '+/'), strlen($encoded) % 4, '='));
        }
    }

    /**
     * @see parent
     */
    public function getRedirectUrl($modelId, $store, $modelType = 'cart')
    {
        return $this->_urls->getFrontendUrl($store, self::REDIRECT_PATH, array(
            '_nosid' => true,
            'service' => 'email',
            'type' => $modelType,
            'id' => urlencode(base64_encode($this->_encrypt->encrypt($modelId)))
        ));
    }

    /**
     * @see parent
     */
    public function isShadowDom($scopeType = 'default', $scopeId = null)
    {
        return (
            $this->isCartRecoveryEnabled($scopeType, $scopeId) &&
            $this->getCartRecoveryEmbedCode($scopeType, $scopeId)
        );
    }
}
