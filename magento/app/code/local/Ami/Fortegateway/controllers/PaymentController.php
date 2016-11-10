<?php
// app/code/local/Ami/Fortegateway/controllers/PaymentController.php
/*
class Ami_Fortegateway_PaymentController extends Mage_Core_Controller_Front_Action 
{
  public function gatewayAction() 
  {
    if ($this->getRequest()->get("orderId"))
    {
      $arr_querystring = array(
        'flag' => 1, 
        'orderId' => $this->getRequest()->get("orderId")
      );
       
      Mage_Core_Controller_Varien_Action::_redirect('fortegateway/payment/response', array('_secure' => false, '_query'=> $arr_querystring));
    }
  }
   
  public function redirectAction() 
  {
    $this->loadLayout();
    $block = $this->getLayout()->createBlock('Mage_Core_Block_Template','fortegateway',array('template' => 'fortegateway/redirect.phtml'));
    $this->getLayout()->getBlock('content')->append($block);
    $this->renderLayout();
  }
 
  public function responseAction() 
  {
    if ($this->getRequest()->get("flag") == "1" && $this->getRequest()->get("orderId")) 
    {
      $orderId = $this->getRequest()->get("orderId");
      $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
      $order->setState(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW, true, 'Payment Success.');
      $order->save();
       
      Mage::getSingleton('checkout/session')->unsQuoteId();
      Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure'=> false));
    }
    else
    {
      Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/error', array('_secure'=> false));
    }
  }
}
*/

/*
class Ami_Fortegateway_PaymentController extends Mage_Core_Controller_Front_Action 
{
  public function gatewayAction() 
  {
    if ($this->getRequest()->get("orderId"))
    {
      $arr_querystring = array(
        'flag' => 1, 
        'orderId' => $this->getRequest()->get("orderId")
      );
       
      Mage_Core_Controller_Varien_Action::_redirect('fortegateway/payment/response', array('_secure' => false, '_query'=> $arr_querystring));
    }
  }
   
  public function redirectAction() 
  {
    $this->loadLayout();
    $block = $this->getLayout()->createBlock('Mage_Core_Block_Template','fortegateway',array('template' => 'fortegateway/redirect.phtml'));
    $this->getLayout()->getBlock('content')->append($block);
    $this->renderLayout();
  }
 
  public function responseAction() 
  {
    if ($this->getRequest()->get("flag") == "1" && $this->getRequest()->get("orderId")) 
    {
      $orderId = $this->getRequest()->get("orderId");
      $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
      $order->setState(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW, true, 'Payment Success.');
      $order->save();
       
      Mage::getSingleton('checkout/session')->unsQuoteId();
      Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure'=> false));
    }
    else
    {
      Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/error', array('_secure'=> false));
    }
  }
}
*/