<?php
class Magecomp_Fileupload_Model_Fileupload extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('fileupload/fileupload');
    }
	
    public function getRelatedProducts($attachmentId)
    {
					
		$fileuploadTable = Mage::getSingleton('core/resource')->getTableName('fileupload_products');
			
		$collection = Mage::getModel('fileupload/fileupload')->getCollection()
					  ->addAttachmentIdFilter($attachmentId)
					  ->addStoreFilter(Mage::app()->getStore(true)->getId());
					  
		$collection->getSelect()
            ->joinLeft(array('related' => $fileuploadTable),
                        'main_table.fileupload_id = related.fileupload_id'
                )
			->order('main_table.fileupload_id');
			
		return $collection->getData();
    }
	
    public function getRelatedAttachments($productId)
    {
					
		$fileuploadTable = Mage::getSingleton('core/resource')->getTableName('fileupload_products');
		$collection = Mage::getModel('fileupload/fileupload')->getCollection();
		$collection->getSelect()
            ->join(array('related' => $fileuploadTable),
                        'main_table.fileupload_id = related.fileupload_id and related.product_id = '.$productId
                )
			->order('main_table.fileupload_id');
			return $collection->getData();
    }
	
	public function updateCounter($id) {
		return $this->_getResource()->updateDownloadsCounter($id);
	}
	
	public function getCMSPage()
	{
		$CMSTable = Mage::getSingleton('core/resource')->getTableName('cms_page');
		$sqry = "select title as label, page_id as value from ".$CMSTable." where is_active=1";	
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$select = $connection->query($sqry);
		return $rows = $select->fetchAll();
	}
}