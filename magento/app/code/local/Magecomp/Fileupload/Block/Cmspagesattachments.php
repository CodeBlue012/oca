<?php
class Magecomp_Fileupload_Block_Cmspagesattachments extends Mage_Catalog_Block_Product_Abstract
{
	const DISPLAY_CONTROLS = 'fileupload/cmspagesattachments/enabled';
	
	protected function _tohtml()
    {
		if (!Mage::getStoreConfig(self::DISPLAY_CONTROLS)) {
			return parent::_toHtml();
		} 
		$this->setLinksforProduct();
		$this->setTemplate("fileupload/cms_attachments.phtml");
		return parent::_toHtml();
    }
	
	public function getCmsPageRelatedAttachments()    {
		
		$dataCurrentPage = $this->getHelper('cms/page')->getPage()->getData();
		$pageid = $dataCurrentPage['page_id'];
	
		$storeId = Mage::app()->getStore()->getId();
		
		$fileuploadTable = Mage::getSingleton('core/resource')->getTableName('fileupload');
		$fileuploadStoreTable = Mage::getSingleton('core/resource')->getTableName('fileupload_store');
		
		$sqry = "SELECT main_table.* FROM ".$fileuploadTable." main_table 
				 INNER JOIN ".$fileuploadStoreTable." AS store_table ON main_table.fileupload_id = store_table.fileupload_id
				 WHERE store_table.store_id in(0,".$storeId.") 
				 AND (main_table.cmspage_id LIKE '%,".$pageid."' or main_table.cmspage_id like '%,".$pageid.",%' or main_table.cmspage_id like '".$pageid.",%' or main_table.cmspage_id = ".$pageid.")";
		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
		$select = $connection->query($sqry);
		$relatedCmsAttachments = $select->fetchAll();	
		return $relatedCmsAttachments;
	}
}