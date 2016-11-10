<?php
// app/code/local/Ami/Fortegateway/Block/Form/Fortegateway.php
/*
class Ami_Fortegateway_Block_Form_Fortegateway extends Mage_Payment_Block_Form
{
  protected function _construct()
  {
    parent::_construct();
    $this->setTemplate('fortegateway/form/fortegateway.phtml');
  }
}
*/

class Ami_Fortegateway_Block_Form_Fortegateway extends Mage_Payment_Block_Form_Ccsave
{
 
}

?>