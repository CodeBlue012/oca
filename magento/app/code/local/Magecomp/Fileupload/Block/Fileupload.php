<?php
class Magecomp_Fileupload_Block_Fileupload extends Mage_Catalog_Block_Product_Abstract
{
	const DISPLAY_CONTROLS = 'fileupload/fileupload/enabled';
	
	protected function _tohtml()
    {
		 if (!Mage::getStoreConfig(self::DISPLAY_CONTROLS))
            return parent::_toHtml();

		$this->setLinksforProduct();
		$this->setTemplate("fileupload/fileupload.phtml");
		return parent::_toHtml();
    }
	
	public function getProductRelatedAttachments()
	{
		$id  = $this->getProduct()->getId(); 		
		$fileuploadTable = Mage::getSingleton('core/resource')->getTableName('fileupload');
		$fileuploadProductsTable = Mage::getSingleton('core/resource')->getTableName('fileupload_products');
		$fileuploadStoreTable = Mage::getSingleton('core/resource')->getTableName('fileupload_store');
		$storeId = Mage::app()->getStore()->getId();
		
		$sqry = "SELECT a.* FROM ".$fileuploadTable." a 
				 INNER JOIN ".$fileuploadStoreTable." AS store_table ON a.fileupload_id = store_table.fileupload_id
				 INNER JOIN ".$fileuploadProductsTable." AS ap ON a.fileupload_id = ap.fileupload_id
				 WHERE ap.product_id = ".$id." AND store_table.store_id in(0,".$storeId.") AND a.status = 1";
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$select = $connection->query($sqry);
		$relatedProductAttachments = $select->fetchAll();	
		return $relatedProductAttachments;
		
	}
}