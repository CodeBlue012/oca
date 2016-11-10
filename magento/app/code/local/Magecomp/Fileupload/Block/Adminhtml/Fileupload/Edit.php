<?php
class Magecomp_Fileupload_Block_Adminhtml_Fileupload_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'fileupload';
        $this->_controller = 'adminhtml_fileupload';
        
        $this->_updateButton('save', 'label', Mage::helper('fileupload')->__('Save File'));
        $this->_updateButton('delete', 'label', Mage::helper('fileupload')->__('Delete File'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('fileupload_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'fileupload_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'fileupload_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('fileupload_data') && Mage::registry('fileupload_data')->getId() ) {
            return Mage::helper('fileupload')->__("Edit File '%s'", $this->htmlEscape(Mage::registry('fileupload_data')->getTitle()));
        } else {
            return Mage::helper('fileupload')->__('Add File');
        }
    }
}