<?php
class Magecomp_Fileupload_Model_Observer_Product extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('fileupload/fileupload', 'fileupload_id');
    }
	
	public function saveTabData(Varien_Event_Observer $observer)
	{
		if ($post = $this->_getRequest()->getPost()) {
			
			$productID = Mage::registry('current_product') ? Mage::registry('current_product')->getId() :null;
			$condition = $this->_getWriteAdapter()->quoteInto('product_id = ?', $productID);
			
			if(isset($post['links']))
			{
				$links = $post['links'];
			}
			
			if (isset($links['related_attachments'])) {
				
				$attachmentIds = Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['related_attachments']);
				$this->_getWriteAdapter()->delete($this->getTable('fileupload_products'), $condition);
				
				foreach ($attachmentIds as $_attachment) {
					$attachmentArray = array();
					$attachmentArray['fileupload_id'] = $_attachment;
					$attachmentArray['product_id'] = $productID;
					$this->_getWriteAdapter()->insert($this->getTable('fileupload_products'), $attachmentArray);
				}
			} 
		}
	}
	
	protected function _getRequest()
	{
		return Mage::app()->getRequest();
	}
}