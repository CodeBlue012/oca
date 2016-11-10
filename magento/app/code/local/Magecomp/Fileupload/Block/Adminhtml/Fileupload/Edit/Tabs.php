<?php
class Magecomp_Fileupload_Block_Adminhtml_Fileupload_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
  	{
		parent::__construct();
		$this->setId('fileupload_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('fileupload')->__('File Information'));
  	}

  	protected function _beforeToHtml()
  	{
		$this->addTab('form_section', array(
			'label'     => Mage::helper('fileupload')->__('File Information'),
			'title'     => Mage::helper('fileupload')->__('File Information'),
			'content'   => $this->getLayout()->createBlock('fileupload/adminhtml_fileupload_edit_tab_form')->toHtml(),
		));
		
		$this->addTab('cms_section', array(
			'label'     => Mage::helper('fileupload')->__('Select CMS Pages'),
			'title'     => Mage::helper('fileupload')->__('Select CMS Pages'),
			'content'   => $this->getLayout()->createBlock('fileupload/adminhtml_fileupload_edit_tab_cmspages')->toHtml(),
		));
		
		$this->addTab('products_section', array(
			  'label'     => Mage::helper('fileupload')->__('Select Products'),
			  'url'       => $this->getUrl('*/*/products', array('_current' => true)),
			  'class'     => 'ajax',
		));
	   
		return parent::_beforeToHtml();
  	}
}