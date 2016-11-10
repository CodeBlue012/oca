<?php
class Magecomp_Fileupload_Model_Mysql4_Fileupload_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('fileupload/fileupload');
    }
	
	public function addAttachmentIdFilter($id = 0)
    {
        $this->getSelect()
            ->where('related.fileupload_id=?', (int)$id);
        return $this;
    }
	
	public function addStoreFilter($store)
    {
		if ($store instanceof Mage_Core_Model_Store) {
            $store = array($store->getId());
        }
		
	    $this->getSelect()->join(
            array('store_table' => $this->getTable('fileupload_store')),
            'main_table.fileupload_id = store_table.fileupload_id',
            array()
        )
        ->where('store_table.store_id in (?)', array(0, $store));
        return $this;
	}
	
	public function addEnableFilter($status)
    {
        $this->getSelect()
            ->where('main_table.status = ?', $status);
        return $this;
    }
}