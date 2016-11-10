<?php
class Magecomp_Fileupload_Model_Mysql4_Productcats extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('fileupload/fileupload_cats', 'category_id');
    }
	
	public function load(Mage_Core_Model_Abstract $object, $value, $field=null)
    {
        if (strcmp($value, (int)$value) !== 0) {
            $field = 'category_url_key';
        }
        return parent::load($object, $value, $field);
    }
    
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('fileupload_category_store'))
            ->where('category_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $storesArray = array();
            foreach ($data as $row) {
                $storesArray[] = $row['store_id'];
            }
            $object->setData('category_store_ids', $storesArray);
        }
	    return parent::_afterLoad($object);
    }
	
	protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('category_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('fileupload_category_store'), $condition);

	    foreach ((array)$object->getData('category_store_ids') as $store) {
            $storeArray = array();
            $storeArray['category_id'] = $object->getId();
            $storeArray['store_id'] = $store;
            $this->_getWriteAdapter()->insert($this->getTable('fileupload_category_store'), $storeArray);
        }

    
        return parent::_afterSave($object);
        
    }

    public static function getCategories()
    {
		$collection = Mage::getModel('fileupload/productcats')->getCollection();

		$collection->printlogquery(true);

        $res = array();

        foreach($collection as $data)
            $res[$data['category_id']] = $data['cat'];
        return $res;
    }

    public static function toOptionArray()
    {
        $res = array();

        foreach(self::getCategories() as $key => $value)
            $res[] = array( 'value' => $key,
                            'label' => $value);

        return $res;
    }

    public function getStoreIds($categoryId)
    {
        if(!$categoryId) return array();

        $db = $this->_getReadAdapter();

        $select = $db->select()
            ->from($this->getTable('fileupload/fileupload_category_store'), 'store_id')
            ->where('category_id=?', $categoryId);

        return $db->fetchCol($select);
    }

    public function saveStoreIds($categoryId, $storeIds)
    {
        if(!is_array($storeIds))
            $storeIds = explode(',', $storeIds);

        $existing = $this->getStoreIds($categoryId);
        $common = array_intersect($existing, $storeIds);
        $deleted = array_diff($existing, $common);
        $new = array_diff($storeIds, $common);

        $db = $this->_getWriteAdapter();

        if(!empty($deleted))
            $db->delete($this->getTable('fileupload/fileupload_category_store'),
                'category_id='.$categoryId.' AND store_id IN ('.implode(',', $deleted).')');

        if(!empty($new))
        {
            $data = array();
            foreach($new as $storeId)
                $data[] = array($categoryId, $storeId);

            	Magecomp_Fileupload_Helper_Data::insertArray($this->getTable('fileupload/fileupload_category_store'),
                array('category_id', 'store_id'),
                $data
            );
        }
        return $this;
    }

    public function getSameUrlCategoryStoreIds($categoryId, $url)
    {
        if(!$url) return array();

        $db = $this->_getReadAdapter();

        $select = $db->select()
            ->from(array('c' => $this->getMainTable()),
                ''
                )
            ->joinInner(array('cs' => $this->getTable('fileupload/fileupload_category_store')),
                    'c.category_id=cs.category_id',
                    array('store_ids' => new Zend_Db_Expr('GROUP_CONCAT(DISTINCT cs.store_id)'))
                )
            ->where('c.category_url_key=?', $url)
            ->group('c.category_id');
        
        if($categoryId)
            $select
                ->where('c.category_id<>?', $categoryId);

        if($res = $db->fetchOne($select))
            return array_unique(explode(',', $res));
        else return array();
    }

    public function getIdByUrlKey($urlKey)
    {
        $db = $this->_getReadAdapter();

        $select = $db->select()
            ->from(array('c' => $this->getMainTable()),
                'category_id'
                )
            ->joinLeft(array('cs' => $this->getTable('fileupload/fileupload_category_store')),
                    'c.category_id=cs.category_id',
                    ''
                )
            ->where('c.category_url_key=?', $urlKey)
            ->where('cs.store_id=?', Mage::app()->getStore()->getId())
            ->limit(1);

        return $db->fetchOne($select);
    }
	
	public function fullTree($parent)
	{
		$db = $this->_getWriteAdapter();
		$stmt = $db->prepare("SELECT node.category_name FROM ".$this->getTable('fileupload/fileupload_cats')." AS node, news_category AS parent
								WHERE node.left_node BETWEEN parent.left_node AND parent.right_node
								AND parent.name = :parent ORDER BY node.left_node");
		$result->bindParam('parent', $parent);
		$result->execute();		
		return $result->fetchALL(PDO::FETCH_ASSOC);
		
	}
	
	public function getGrid(){		
		$db = $this->_getWriteAdapter();
		$stmt = $db->prepare("	SELECT CONCAT( REPEAT( '  ', (COUNT(parent.category_name) - 1) ), node.category_name) AS category_name, node.category_id
								,node.category_status,node.category_url_key,node.category_order,node.left_node,node.right_node
								FROM ".$this->getTable('fileupload/fileupload_cats')." AS node,
								".$this->getTable('fileupload/fileupload_cats')." AS parent
								WHERE node.left_node BETWEEN parent.left_node AND parent.right_node
								GROUP BY node.category_name
								ORDER BY node.left_node");
		$stmt->execute();		
		return $stmt->fetchALL(PDO::FETCH_ASSOC);
		
	}
	
	public function leafNodes(){
		$db = $this->_getWriteAdapter();
		$stmt = $db->prepare("SELECT category_name FROM ".$this->getTable('fileupload/fileupload_cats')." WHERE right_node = left_node + 1");
		$stmt->execute();
		return $stmt->fetchALL(PDO::FETCH_ASSOC);
	}
	
	public function singlePath($node_id){
		$db = $this->_getWriteAdapter();
		$stmt = $db->prepare("SELECT parent.category_name FROM ".$this->getTable('fileupload/fileupload_cats')." AS node, ".$this->getTable('fileupload/fileupload_cats')." AS parent WHERE node.left_node BETWEEN parent.left_node AND parent.right_node AND node.category_id = '{$node_id}' ORDER BY node.left_node");
		$stmt->execute();
		return $stmt->fetchALL(PDO::FETCH_ASSOC);
	}
	
	public function getNodeDepth()
	{
		$db = $this->_getWriteAdapter();
		$stmt = $db->prepare("SELECT node.category_name, (COUNT(parent.name) - 1) AS depth FROM ".$this->getTable('fileupload/fileupload_cats')." AS node, ".$this->getTable('fileupload/fileupload_cats')." AS parent WHERE node.left_node BETWEEN parent.left_node AND parent.right_node GROUP BY node.category_name ORDER BY node.left_node");
		$stmt->execute();
		return $stmt->fetchALL(PDO::FETCH_ASSOC);
	}
	
	public function subTreeDepth($node_id)
	{
		$db = $this->_getWriteAdapter();
		$stmt = $db->prepare("SELECT node.category_name, (COUNT(parent.category_name) - 1) AS depth FROM ".$this->getTable('fileupload/fileupload_cats')." AS node, ".$this->getTable('fileupload/fileupload_cats')." AS parent WHERE node.left_node BETWEEN parent.left_node AND parent.right_node AND node.category_id = :node_id GROUP BY node.category_name ORDER BY node.left_node");
		$stmt->bindParam(':node_id', $node_id, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetchALL(PDO::FETCH_ASSOC);
	}
	
	public function getLocalSubNodes($node_name)
	{
		$db = $this->_getWriteAdapter();
		$stmt = $db->prepare("SELECT node.category_id,node.category_name, (COUNT(parent.category_name) - (sub_tree.depth + 1)) AS depth,node.*
								FROM ".$this->getTable('fileupload/fileupload_cats')." AS node,
									".$this->getTable('fileupload/fileupload_cats')." AS parent,
									".$this->getTable('fileupload/fileupload_cats')." AS sub_parent,
									(
										SELECT node.category_name, (COUNT(parent.category_name) - 1) AS depth
										FROM ".$this->getTable('fileupload/fileupload_cats')." AS node,
										".$this->getTable('fileupload/fileupload_cats')." AS parent
										WHERE node.left_node BETWEEN parent.left_node AND parent.right_node
										AND node.category_name = :node_name
										GROUP BY node.category_name
										ORDER BY node.left_node
									)AS sub_tree
									WHERE node.left_node BETWEEN parent.left_node AND parent.right_node
									AND node.left_node BETWEEN sub_parent.left_node AND sub_parent.right_node
									AND sub_parent.category_name = sub_tree.category_name
								GROUP BY node.category_name
								HAVING depth = 1
								ORDER BY node.left_node;");
		$stmt->bindParam(':node_name', $node_name, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetchALL(PDO::FETCH_ASSOC);
	}
	
	public function productCount()
	{
		$db = $this->_getWriteAdapter();
		$stmt = $db->prepare("SELECT parent.category_name, COUNT(products.category_name) AS product_count FROM ".$this->getTable('fileupload/fileupload_cats')." AS node ,".$this->getTable('fileupload/fileupload_cats')." AS parent, products  WHERE node.left_node BETWEEN parent.left_node AND parent.right_node AND node.category_id = products.category_id GROUP BY parent.category_name ORDER BY node.left_node");
		$stmt->execute();
		return $stmt->fetchALL(PDO::FETCH_ASSOC);
	}
	
	public function getParentNodeID($node_id){
		$db = $this->_getWriteAdapter();
		$stmt = $db->prepare("SELECT parent_id FROM ".$this->getTable('fileupload/fileupload_cats')." WHERE category_id = $node_id");
		$stmt->execute();
		return $stmt->fetchALL(PDO::FETCH_ASSOC);
	}
	
	public function addNode($node_id,$new_node)
	{
		$db = $this->_getWriteAdapter();
		try {
				$db->beginTransaction();
				$stmt = $db->prepare("SELECT @myRight := right_node FROM ".$this->getTable('fileupload/fileupload_cats')." WHERE category_id = :node_id");
				$stmt->bindParam(':node_id', $node_id);
				$stmt->execute();
						
				/*** increment the nodes by two ***/
				$db->exec("UPDATE ".$this->getTable('fileupload/fileupload_cats')." SET right_node = right_node + 2 WHERE right_node > @myRight");
				$db->exec("UPDATE ".$this->getTable('fileupload/fileupload_cats')." SET left_node = left_node + 2 WHERE left_node > @myRight");
		
				$stmt = $db->prepare("SELECT @myRight + 1 as lft, @myRight + 2 as rgt");
				$stmt->execute();
				/*** commit the transaction ***/
				$db->commit();	
			} catch(Exception $e) {
				$db->rollBack();
				throw new Exception($e);
			}
			return $stmt->fetchALL(PDO::FETCH_ASSOC);
	}
	
	public function addChildNode($node_id, $new_node)
	{
		$db = $this->_getWriteAdapter();
		try {
				$db->beginTransaction();
				$stmt = $db->prepare("SELECT @myLeft := left_node FROM ".$this->getTable('fileupload/fileupload_cats')." WHERE category_id=:node_id");
				$stmt->bindParam(':node_id', $node_id);
				$stmt->execute();
				
				$db->exec("UPDATE ".$this->getTable('fileupload/fileupload_cats')." SET right_node = right_node + 2 WHERE right_node > @myLeft");
				$db->exec("UPDATE ".$this->getTable('fileupload/fileupload_cats')." SET left_node = left_node + 2 WHERE left_node > @myLeft");
				
				$stmt = $db->prepare("SELECT @myLeft + 1 as lft, @myLeft + 2 as rgt");
				$stmt->execute();
				$db->commit();
			} catch(Exception $e) {
				$db->rollBack();
				throw new Exception($e);
			}
			return $stmt->fetchALL(PDO::FETCH_ASSOC);
	}
	
	public function deleteLeafNode($node_id)
	{
		$db = $this->_getWriteAdapter();
		try {
				$db->beginTransaction();
				$stmt = $db->prepare("SELECT @myLeft := left_node, @myRight := right_node, @myWidth := right_node - left_node + 1 FROM ".$this->getTable('fileupload/fileupload_cats')." WHERE category_id = :node_id");
				$stmt->bindParam(':node_id', $node_id);
				$stmt->execute();
				$db->exec("DELETE FROM ".$this->getTable('fileupload/fileupload_cats')." WHERE left_node BETWEEN @myLeft AND @myRight");
				$db->exec("UPDATE ".$this->getTable('fileupload/fileupload_cats')." SET right_node = right_node - @myWidth WHERE right_node > @myRight");
				$db->exec("UPDATE ".$this->getTable('fileupload/fileupload_cats')." SET left_node = left_node - @myWidth WHERE left_node > @myRight");
				$db->commit();
			} catch(Exception $e) {
				$db->rollBack();
				throw new Exception($e);
			}
	}
	
	public function deleteNodeRecursive($node_id)
	{
		$db = $this->_getWriteAdapter();
		try {
			$db->beginTransaction();
			$db->exec("DELETE FROM ".$this->getTable('fileupload/fileupload_cats')." WHERE category_id = $node_id");
			$db->exec("DELETE FROM ".$this->getTable('fileupload/fileupload_category_link')." WHERE category_id = $node_id");
			$db->exec("DELETE FROM ".$this->getTable('fileupload/fileupload_category_store')." WHERE category_id = $node_id");
			$db->commit();
			
		} catch(Exception $e) {
			$db->rollBack();
			throw new Exception($e);
		}
	}
	
	public function setNodeStatusRecursive($node_id,$status)
	{
		$db = $this->_getWriteAdapter();
		try {
				$db->beginTransaction();
				$db->exec("UPDATE ".$this->getTable('fileupload/fileupload_cats')." SET category_status = $status WHERE category_id = $node_id");
				$db->commit();
				
			} catch(Exception $e) {
				$db->rollBack();
				throw new Exception($e);
			}
	}
}