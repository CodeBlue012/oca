<?php

class Ami_Subscriptioninfo_Model_Resource_Orders
	extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Resource initialization
     *
     */
    protected function _construct()
    {
        $this->_init( 'subscriptioninfo/orders', 'item_id' );
    }
}