<?php
class Magecomp_Fileupload_Model_Mysql4_Fileupload extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('fileupload/fileupload', 'fileupload_id');
    }
	
	public function load(Mage_Core_Model_Abstract $object, $value, $field=null)
    {
        if (strcmp($value, (int)$value) !== 0) {
            $field = 'fileupload_id';
        }
        return parent::load($object, $value, $field);
    }
	
	 protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
    	
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('fileupload_store'))
            ->where('fileupload_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $storesArray = array();
            foreach ($data as $row) {
                $storesArray[] = $row['store_id'];
            }
            $object->setData('store_id', $storesArray);
        }
        return parent::_afterLoad($object);
    }
	
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
		
        $condition = $this->_getWriteAdapter()->quoteInto('fileupload_id = ?', $object->getId());
    	$this->_getWriteAdapter()->delete($this->getTable('fileupload_store'), $condition);
		 
		foreach ((array)$object->getData('stores') as $store) {
            $storeArray = array();
            $storeArray['fileupload_id'] = $object->getId();
            $storeArray['store_id'] = $store;
            $this->_getWriteAdapter()->insert($this->getTable('fileupload_store'), $storeArray);
        }
		
		$links = $object['links'];
		if (isset($links['related'])) {
			$productIds = Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['related']);
			$this->_getWriteAdapter()->delete($this->getTable('fileupload_products'), $condition);
			
			foreach ($productIds as $_product) {
				$newsArray = array();
				$newsArray['fileupload_id'] = $object->getId();
				$newsArray['product_id'] = $_product;
				$this->_getWriteAdapter()->insert($this->getTable('fileupload_products'), $newsArray);
			}
		} 
        return parent::_afterSave($object);
    }
	
	public function updateDownloadsCounter($id){
		$attachmentsTable = Mage::getSingleton('core/resource')->getTableName('fileupload');		
		$db = $this->_getWriteAdapter();
		try {
				$db->beginTransaction();
				$db->exec("UPDATE ".$attachmentsTable." SET downloads = (downloads+1) WHERE fileupload_id = $id");
				$db->commit();
				
			} catch(Exception $e) {
				$db->rollBack();
				throw new Exception($e);
			}
	}
}