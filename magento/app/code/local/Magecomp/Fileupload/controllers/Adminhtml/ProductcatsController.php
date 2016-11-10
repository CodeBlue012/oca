<?php
class Magecomp_Fileupload_Adminhtml_ProductcatsController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('fileupload/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('File Uploads Categories'), Mage::helper('adminhtml')->__('File Uploads Categories'));
		
		return $this;
	}  

    public function indexAction() { $this->_initAction()->renderLayout(); }

    public function newAction() { $this->_forward('edit'); }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $data = Mage::getModel('fileupload/productcats')->load($id)->getData();
        $session = Mage::getSingleton('adminhtml/session');

        if(isset($data['category_id']) || $id == 0)
        {
            $sessionData = $session->getKBaseCategoryData(true);
            $session->setKBaseCategoryData(false);

            if(is_array($sessionData)) $data = array_merge($data, $sessionData);

            if(isset($data['category_url_key']))
                $data['category_url_key'] = urldecode($data['category_url_key']);

            Mage::register('fileupload_productcats', $data);

            $this->loadLayout();
         $this->_setActiveMenu('fileupload/items');

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('fileupload/adminhtml_productcats_edit'))
                ->_addLeft($this->getLayout()->createBlock('fileupload/adminhtml_productcats_edit_tabs'));

            $this->renderLayout();
        }
        else
        {
            $session->addError($this->__('Category does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {
        $session = Mage::getSingleton('adminhtml/session');

        if($data = $this->getRequest()->getPost())
        {
            try
            {
                $id = $this->getRequest()->getParam('id');
                if( !isset($data['category_url_key']) ||  !$data['category_url_key'] )   
				$data['category_url_key'] = $data['category_name'];
				
                $data['category_url_key'] = Magecomp_Fileupload_Helper_Data::nameToUrlKey($data['category_url_key']);

                $model = Mage::getModel('fileupload/productcats')
                            ->setData($data)
                            ->setId($id);

                if($model->isUrlKeyUsed())
                {
                    $session->addError($this->__('URL key is not unique within category store views'));
                    $session->setKBaseCategoryData($data);
                    $this->_redirect('*/*/edit', array('id' => $id));
                    return;
                }
				
				$result = $model->addChildCategory($data['parent_id'],$data['category_name']);
				$data['right_node'] =  $result[0]['rgt'];
				$data['left_node']  =  $result[0]['lft'];
				
				$model = Mage::getModel('fileupload/productcats')
                            ->setData($data)
                            ->setId($id);				
				$model->save();
				
                $session->addSuccess($this->__('Category was successfully saved'));
                $session->setKBaseCategoryData(false);

                if($this->getRequest()->getParam('back'))
                {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Exception $e)
            {
                Mage::logException($e);
                $session->addError($e->getMessage());
                $session->setKBaseCategoryData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        else
        {
            $session->addError($this->__('Unable to find an category to save'));
            $this->_redirect('*/*/');
        }
    }

    public function deleteAction()
    {
        $session = Mage::getSingleton('adminhtml/session');

        if($id = $this->getRequest()->getParam('id'))
        {
            try
            {
                Mage::getModel('fileupload/productcats')->deleteCategory($id);
                $session->addSuccess($this->__('Category was successfully deleted'));
                $this->_redirect('*/*/');
            }
            catch (Exception $e)
            {
                Mage::logException($e);
                $session->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $id));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $session = Mage::getSingleton('adminhtml/session');
        $kbaseIds = $this->getRequest()->getParam('category_ids');

        if(!is_array($kbaseIds)) {
            $session->addError($this->__('Please select category(s)'));
        }
        else
        {
            try
            {
                $model = Mage::getModel('fileupload/productcats');

                foreach($kbaseIds as $kbaseId)
                    Mage::getModel('fileupload/productcats')->deleteCategory($kbaseId);

                $session->addSuccess($this->__('Total of %d category(s) were successfully deleted', count($kbaseIds)));
            }
            catch (Exception $e)
            {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction()
    {
        $session = Mage::getSingleton('adminhtml/session');
        $kbaseIds = $this->getRequest()->getParam('category_ids');
        if(!is_array($kbaseIds)) {
            $session->addError($this->__('Please select item(s)'));
        }
        else
        {
            try
            {
                foreach($kbaseIds as $kbaseId)
				Mage::getModel('fileupload/productcats')->changeStatus($kbaseId,$this->getRequest()->getParam('status'));
                   
                $session->addSuccess($this->__('Total of %d category(s) were successfully updated', count($kbaseIds)));
            }
            catch (Exception $e)
            {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}