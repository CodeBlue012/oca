<?php
abstract class Magecomp_Fileupload_Model_Abstract extends Varien_Object
{
    protected $_eventPrefix = 'core_abstract';

    protected $_eventObject = 'object';

    protected $_resourceName;

    protected $_resource;

    protected $_resourceCollectionName;

    protected $_cacheTag    = false;

    protected $_dataSaveAllowed = true;

    protected function _init($resourceModel)
    {
        $this->_setResourceModel($resourceModel);
    }

    protected function _setResourceModel($resourceName, $resourceCollectionName=null)
    {
        $this->_resourceName = $resourceName;
        if (is_null($resourceCollectionName)) {
            $resourceCollectionName = $resourceName.'_collection';
        }
        $this->_resourceCollectionName = $resourceCollectionName;
    }

    protected function _getResource()
    {
        if (empty($this->_resourceName)) {
            Mage::throwException(Mage::helper('core')->__('Resource is not set'));
        }

        return Mage::getResourceSingleton($this->_resourceName);
    }

    public function getIdFieldName()
    {
        if (!($fieldName = parent::getIdFieldName())) {
            $fieldName = $this->_getResource()->getIdFieldName();
            $this->setIdFieldName($fieldName);
        }
        return $fieldName;
    }

    public function getId()
    {
        if ($fieldName = $this->getIdFieldName()) {
            return $this->_getData($fieldName);
        } else {
            return $this->_getData('id');
        }
    }

    public function setId($id)
    {
        if ($this->getIdFieldName()) {
            $this->setData($this->getIdFieldName(), $id);
        } else {
            $this->setData('id', $id);
        }
        return $this;
    }

    public function getResourceName()
    {
        return $this->_resourceName;
    }

    public function getResourceCollection()
    {
        if (empty($this->_resourceCollectionName)) {
            Mage::throwException(Mage::helper('core')->__('Model collection resource name is not defined'));
        }
        return Mage::getResourceModel($this->_resourceCollectionName, $this->_getResource());
    }

    public function getCollection()
    {
        return $this->getResourceCollection();
    }

    public function load($id, $field=null)
    {
        $this->_getResource()->load($this, $id, $field);
        $this->_afterLoad();
        $this->setOrigData();
        return $this;
    }
	
    protected function _afterLoad()
    {
        Mage::dispatchEvent('model_load_after', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_load_after', array($this->_eventObject=>$this));
        return $this;
    }

    public function afterLoad()
    {
        $this->getResource()->afterLoad($this);
        $this->_afterLoad();
    }

    public function save()
    {
        $this->_getResource()->beginTransaction();
        try {
            $this->_beforeSave();
            if ($this->_dataSaveAllowed) {
                $this->_getResource()->save($this);
                $this->_afterSave();
            }
            $this->_getResource()->commit();
        }
        catch (Exception $e){
            $this->_getResource()->rollBack();
            throw $e;
        }
        return $this;
    }

    protected function _beforeSave()
    {
        Mage::dispatchEvent('model_save_before', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_save_before', array($this->_eventObject=>$this));
        return $this;
    }

    protected function _afterSave()
    {
        if ($this->_cacheTag) {
            if ($this->_cacheTag === true) {
                $tags = array();
            }
            else {
                $tags = array($this->_cacheTag);
            }
            Mage::app()->cleanCache($tags);
        }
        Mage::dispatchEvent('model_save_after', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_save_after', array($this->_eventObject=>$this));
        return $this;
    }

    public function delete()
    {
        $this->_getResource()->beginTransaction();
        try {
            $this->_beforeDelete();
            $this->_getResource()->delete($this);
            $this->_afterDelete();

            $this->_getResource()->commit();
        }
        catch (Exception $e){
            $this->_getResource()->rollBack();
            throw $e;
        }
        return $this;
    }

    protected function _beforeDelete()
    {
        Mage::dispatchEvent('model_delete_before', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_delete_before', array($this->_eventObject=>$this));
        return $this;
    }

    protected function _protectFromNonAdmin()
    {
        if (Mage::registry('isSecureArea')) {
            return;
        }
        if (!Mage::app()->getStore()->isAdmin()) {
            Mage::throwException(Mage::helper('core')->__('Cannot complete this operation from non-admin area.'));
        }
    }

    protected function _afterDelete()
    {
        if ($this->_cacheTag) {
            if ($this->_cacheTag === true) {
                $tags = array();
            }
            else {
                $tags = array($this->_cacheTag);
            }
            Mage::app()->cleanCache($tags);
        }
        Mage::dispatchEvent('model_delete_after', array('object'=>$this));
        Mage::dispatchEvent($this->_eventPrefix.'_delete_after', array($this->_eventObject=>$this));
        return $this;
    }

    public function getResource()
    {
        return $this->_getResource();
    }

    public function getEntityId()
    {
        return $this->_getData('entity_id');
    }
}