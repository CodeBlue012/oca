<?php

class Brontosoftware_Optin_Model_Settings extends Brontosoftware_Magento_Optin_Settings
{
    /**
     * Override for DI
     */
    public function __construct()
    {
        parent::__construct(Mage::getModel('brontosoftware_connector/impl_core_scoped'));
    }
}
