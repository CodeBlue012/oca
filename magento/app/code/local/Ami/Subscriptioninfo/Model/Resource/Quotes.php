<?php

class Ami_Subscriptioninfo_Model_Resource_Quotes
	extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Resource initialization
     *
     */
    protected function _construct()
    {
        $this->_init( 'subscriptioninfo/quotes', 'item_id' );
    }
}