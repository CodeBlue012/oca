<?php
class Ami_Customextra_Block_Adminhtml_Order extends Mage_Adminhtml_Block_Sales_Order_Abstract
{
    public function load(){
        $order = Mage::getModel('sales/order');
        return $order;
    }
    public function getAccountNo()
    {
        $order = Mage::getModel('sales/order')->getOrder();
        return $order;
    }
}