<?php
class Magecomp_Fileupload_Model_Productcats extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('fileupload/productcats');
    }

	public function addParnetCategory($left_node, $new_node)
	{
		return $this->getResource()->addNode($left_node, $new_node);
	}
	
	public function addChildCategory($left_node){
		return $this->getResource()->addChildNode($left_node,$left_node+1);
	}
	
	public function deleteCategory($nodeId){
		return $this->getResource()->deleteNodeRecursive($nodeId);
	}
	
	public function changeStatus($node_id,$status){
		return $this->getResource()->setNodeStatusRecursive($node_id,$status);
	}
	
	public function getChilderns($node_name){
		return $this->getResource()->getLocalSubNodes($node_name);
	}
	
	public function getParentID($node_id){
		return $this->getResource()->getParentNodeID($node_id);
	}
	
	public function getGridData() {
		return $this->getResource()->getGrid();
	}

    public function isUrlKeyUsed()
    {
        $storeIds = $this->getCategoryStoreIds();
        if(!is_array($storeIds)) $storeIds = explode(',', $storeIds);

        $sameUrlCategoryStoreIds = $this->getResource()->getSameUrlCategoryStoreIds($this->getId(), $this->getCategoryUrlKey());

        $res = array_intersect($storeIds, $sameUrlCategoryStoreIds);
        return !empty($res);
    }

    protected function _afterLoad()
    {
        if(is_null($storeIds = $this->getCategoryStoreIds()))
            $this->setCategoryStoreIds($this->getResource()->getStoreIds($this->getId()));
        elseif(!is_array($storeIds))
            $this->setCategoryStoreIds(array_unique(explode(',', $storeIds)));

        return parent::_afterLoad();
    }

    public function afterLoad()
    {
        $this->_afterLoad();
    }

    protected function _afterSave()
    {
        $this->getResource()->saveStoreIds($this->getId(), $this->getCategoryStoreIds());
        return parent::_afterSave();
    }

    public function loadByUrlKey($urlKey)
    {
        $id = $this->getResource()->getIdByUrlKey($urlKey);
        return $this->load($id);
    }
}