<?php
class Magecomp_Fileupload_Model_Mysql4_Productcats_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('fileupload/fileupload');
    }

    public function getSelectCountSql()
    {
        $this->_renderFilters();

        $countSelect = clone $this->getSelect();

        $countSelect->reset(Zend_Db_Select::ORDER);
        $countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $countSelect->reset(Zend_Db_Select::COLUMNS);
        $countSelect->reset(Zend_Db_Select::GROUP);
        $countSelect->reset(Zend_Db_Select::HAVING);

        $countSelect->from('', 'COUNT(DISTINCT main_table.category_id)');

        return $countSelect;
    }

    public function getAllIds()
    {
        $idsSelect = clone $this->getSelect();
        $idsSelect->reset(Zend_Db_Select::ORDER);
        $idsSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $idsSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $idsSelect->reset(Zend_Db_Select::COLUMNS);
        $idsSelect->reset(Zend_Db_Select::HAVING);
        $idsSelect->from(null, 'main_table.'.$this->getResource()->getIdFieldName());
        return $this->getConnection()->fetchCol($idsSelect);
    }
	
	public function addStatusFilter($enabled = 1)
    {
        $this->getSelect()
            ->where('main_table.category_status=?', (int)$enabled);

        return $this;
    }
	
	public function addRootCategoryFilter($id = 0)
    {
        $this->getSelect()
            ->where('main_table.parent_id!=?', (int)$id);

        return $this;
    }
	
	public function addPortfolioFilter($id = 0)
    {
        $this->getSelect()
            ->where('link.news_id=?', (int)$id);

        return $this;
    }
    public function addCategoryFilter($cat)
    {
       
        $this->getSelect()
        ->where('category_id= ?', $cat);

        return $this;
    }
}