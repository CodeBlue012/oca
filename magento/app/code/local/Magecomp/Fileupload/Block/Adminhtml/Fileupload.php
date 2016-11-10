<?php
class Magecomp_Fileupload_Block_Adminhtml_Fileupload extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  	public function __construct()
  	{
    	$this->_controller = 'adminhtml_fileupload';
    	$this->_blockGroup = 'fileupload';
    	$this->_headerText = Mage::helper('fileupload')->__('File Upload Manager');
    	$this->_addButtonLabel = Mage::helper('fileupload')->__('Add File');
    	parent::__construct();
  	}
}