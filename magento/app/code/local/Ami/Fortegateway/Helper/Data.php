<?php
// app/code/local/Ami/Fortegateway/Helper/Data.php
class Ami_Fortegateway_Helper_Data extends Mage_Core_Helper_Abstract
{
  function getPaymentGatewayUrl() 
  {
    return Mage::getUrl('fortegateway/payment/gateway', array('_secure' => false));
  }
}