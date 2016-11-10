<?php
class Magecomp_Fileupload_Block_Adminhtml_Productcats_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('meta_fieldset', array('legend' => Mage::helper('fileupload')->__('Meta Data')));
             
    	$fieldset->addField('meta_keywords', 'editor', array(
            'name'		=> 'meta_keywords',
            'label'		=> Mage::helper('fileupload')->__('Keywords'),
            'title'		=> Mage::helper('fileupload')->__('Meta Keywords'),
    		'required'	=> false
        ));

    	$fieldset->addField('meta_description', 'editor', array(
            'name'		=> 'meta_description',
            'label'		=> Mage::helper('fileupload')->__('Description'),
            'title'		=> Mage::helper('fileupload')->__('Meta Description'),
    		'required'	=> false
        ));
        
		$data = Mage::registry('fileupload_productcats');		
	 	$form->setValues($data);
        return parent::_prepareForm();
    }
}