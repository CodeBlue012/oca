<?php
class Magecomp_Fileupload_Block_Adminhtml_Fileupload_Edit_Tab_Cmspages extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('fileupload_form', array('legend'=>Mage::helper('fileupload')->__('Attach With CMS Pages')));
        
		 $fieldset->addField('cmspage_id','multiselect',array(
			'name'      => 'cmspage_id',
            'label'     => Mage::helper('fileupload')->__('CMS Pages'),
            'title'     => Mage::helper('fileupload')->__('CMS Pages'),
            'required'  => false,
	    	'values'    => Mage::getModel('fileupload/fileupload')->getCMSPage()
	  	));
		
		$data = Mage::registry('fileupload_data');		
	 	$form->setValues($data);
        return parent::_prepareForm();
    }
}