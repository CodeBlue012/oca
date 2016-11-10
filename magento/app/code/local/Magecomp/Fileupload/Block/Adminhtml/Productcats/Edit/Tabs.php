<?php
class Magecomp_Fileupload_Block_Adminhtml_Productcats_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
    {
        parent::__construct();
        $this->setId('productcats_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->__('Category Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('general', array(
            'label'     => $this->__('General'),
            'title'     => $this->__('General'),
            'content'   => $this->getLayout()->createBlock('fileupload/adminhtml_productcats_edit_tab_general')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}