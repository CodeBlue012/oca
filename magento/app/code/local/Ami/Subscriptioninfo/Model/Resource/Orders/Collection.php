<?php

class Ami_Subscriptioninfo_Model_Resource_Orders_Collection
	extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Resource collection initialization
     *
     */
    protected function _construct()
    {
        $this->_init( 'subscriptioninfo/orders' );
    }
	
	
/*
	public function joinSales()
	{
		$this->getSelect()
                ->join( array( 'T1' => $this->getTable( 'sales/order' ) ),
                        'main_table.order_entity_id = T1.entity_id',
                        array( 'increment_id' => 'T1.increment_id',
							   'created_at'   => 'T1.created_at'  ) )
				->join( array( 'T2' => $this->getTable( 'sales/order_item' ) ),
					    'main_table.order_entity_id = T2.order_id AND
						 main_table.product_id = T2.product_id',
						array(  'name', 'sku' ) );
		
        return $this;
	}
*/
}    