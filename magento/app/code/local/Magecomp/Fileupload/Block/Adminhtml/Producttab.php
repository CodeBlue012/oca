<?php
class Magecomp_Fileupload_Block_Adminhtml_Producttab extends Mage_Adminhtml_Block_Widget_Grid implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('attachmentsGrid');
		$this->setDefaultSort('fileupload_id');
		$this->setDefaultDir('ASC');
		$this->setUseAjax(true);
		$this->setSaveParametersInSession(false);	
	}
	
	public function getTabLabel(){
		return Mage::helper('core')->__('Attachments');
	}
	
	public function getTabTitle(){
		return Mage::helper('core')->__('Attachments');
	}
	
	public function canShowTab(){
		return true;
	}
	
	public function isHidden(){
		return false;
	}
	
	protected function _prepareCollection()
  	{
		$collection = Mage::getModel('fileupload/fileupload')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
  	}

	protected function _prepareColumns()
	{
		$this->addColumn('in_attachments', array(
			  'header_css_class'  => 'a-center',
			  'type'              => 'checkbox',
			  'name'              => 'in_attachments',
			  'align'             => 'center',
			  'index'             => 'fileupload_id'
		  ));
		
		$this->addColumn('fileupload_id', array(
			'header'    => Mage::helper('fileupload')->__('ID'),
			'align'     =>'right',
			'width'     => '50px',
			'index'     => 'fileupload_id',
		));
  
		$this->addColumn('title', array(
			'header'    => Mage::helper('fileupload')->__('Title'),
			'align'     =>'left',
			'index'     => 'title',
		));
  
		$this->addColumn('status', array(
			'header'    => Mage::helper('fileupload')->__('Status'),
			'align'     => 'left',
			'width'     => '80px',
			'index'     => 'status',
			'type'      => 'options',
			'options'   => array(
				1 => 'Enabled',
				2 => 'Disabled',
			),
		));
		
		return parent::_prepareColumns();
	}

  	public function getRowUrl($row)
  	{
    	return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  	}
}
?>