<?php
class Magecomp_Fileupload_Block_Catalog_Product_Edit_Tab_Attachments extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('product_attachments_grid');
		$this->setDefaultSort('fileupload_id');
		$this->setUseAjax(true);	
		$this->setDefaultFilter(array('in_attachments'=>1));
	}
	
	protected function _getProduct()
	{
		return Mage::registry('current_product_attachments');
	}

    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_attachments') {
            $attachmentIds = $this->_getSelectedAttachments();
            if (empty($attachmentIds)) {
                $attachmentIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('fileupload_id', array('in'=>$attachmentIds));
            } else {
                if($attachmentIds) {
                    $this->getCollection()->addFieldToFilter('fileupload_id', array('nin'=>$attachmentIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

	protected function _prepareCollection ()
	{
	    $collection = Mage::getModel('fileupload/fileupload')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
  
    public function isReadonly()
    {
        return 0;
    }

   	protected function _prepareColumns()
  	{
		$this->addColumn('in_attachments', array(
			  'header_css_class'  => 'a-center',
			  'type'              => 'checkbox',
			  'name'              => 'in_attachments',
			  'values'            => $this->_getSelectedAttachments(),
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

  	public function getGridUrl()
	{
    	return $this->getData('grid_url') ? $this->getData('grid_url') : $this->getUrl('*/*/attachmentsGrid', array('_current' => true));
  	}
  
  	public function _getSelectedAttachments()
    {
        $products = $this->getProductRelatedAttachments();	
        if (!is_array($products)) {
            $products = array_keys($this->getProductAttachments());
        }
        return $products;
    }
  
  	public function getProductAttachments()
    {
		$productid = $this->getRequest()->getParam('id');
       	$productsArr = array();
        foreach (Mage::registry('current_product_attachments')->getRelatedAttachments($productid) as $products) {
           $productsArr[$products["fileupload_id"]] = array('position' => '0');
        }
        return $productsArr;

    }
}
?>
