<?php
class Magecomp_Fileupload_Block_Catalog_Product_Edit_Tabs extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs{
 	protected function _prepareLayout()
    {
        $ret = parent::_prepareLayout();
        
        $product = $this->getProduct();

        if (!($setId = $product->getAttributeSetId())) {
            $setId = $this->getRequest()->getParam('set', null);
        }

        if ($setId){
			$this->addTab('attachments', array(
                'label'     => Mage::helper('catalog')->__('Attachments'),
                'url'       => $this->getUrl('fileupload/catalog_product/attachments', array('_current' => true)),
                'class'     => 'ajax',
            ));
        }
        else {;}
        return $ret;
	} 
}

?>
