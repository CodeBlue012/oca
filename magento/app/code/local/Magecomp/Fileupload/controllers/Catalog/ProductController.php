<?php
require_once 'Mage/Adminhtml/controllers/Catalog/ProductController.php';
class Magecomp_Fileupload_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController 
{
	public function preDispatch()
	{
		parent::preDispatch();
		$this->getRequest()->setRouteName('fileupload');
	} 
  
  	public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('adminhtml/catalog_product_grid')->toHtml()
        );
    }

    public function gridOnlyAction()
    {
        $this->_initProduct();

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_' . $this->getRequest()->getParam('gridOnlyBlock'))
                ->toHtml()
        );
    }

  	public function attachmentsGridAction()
	{
		$this->_initProduct();
		$this->_initProductAttachments();
        $this->loadLayout();
        $this->getLayout()->getBlock('catalog.product.edit.tab.attachments')
            ->setProductRelatedAttachments($this->getRequest()->getPost('products_related_attachments', null));
        $this->renderLayout();
	}
	
	public function attachmentsAction()
	{
		$this->_initProduct();
		$this->_initProductAttachments();
        $this->loadLayout();
        $this->getLayout()->getBlock('catalog.product.edit.tab.attachments')
            ->setProductRelatedAttachments($this->getRequest()->getPost('products_related_attachments', null));
        $this->renderLayout();
	}
  
	protected function _initProduct($block=null)
	{
		static $product = null;
		if($block===false){
			$product = null;
			return;
		}
		if(!$product){
			$r = parent::_initProduct();
			if($block===true)
				$product = $r;
			return($r);
		} else {
			return($product);
		}
	}
  
    protected function _initProductAttachments()
	{
		$fileupload = Mage::getModel('fileupload/fileupload');
		Mage::register('current_product_attachments', $fileupload);
		return $fileupload;
	}
}
?>