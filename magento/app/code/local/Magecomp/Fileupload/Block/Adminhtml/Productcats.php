<?php
class Magecomp_Fileupload_Block_Adminhtml_Productcats extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  	public function __construct()
  	{
    	$this->_controller = 'adminhtml_productcats';
    	$this->_blockGroup = 'fileupload';
    	$this->_headerText = Mage::helper('fileupload')->__('File Uploads Category Manager');
    	$this->_addButtonLabel = Mage::helper('fileupload')->__('Add Category');
    	parent::__construct();
  	}
}