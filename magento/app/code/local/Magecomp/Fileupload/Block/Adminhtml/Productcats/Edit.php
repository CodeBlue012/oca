<?php
class Magecomp_Fileupload_Block_Adminhtml_Productcats_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'fileupload';
        $this->_controller = 'adminhtml_productcats';

        $this->_updateButton('save', 'label', $this->__('Save Category'));
        $this->_updateButton('delete', 'label', $this->__('Delete Category'));

        $this->_addButton('saveandcontinue', array(
            'label'     => $this->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('fileupload_productcats') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'fileupload_productcats');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'fileupload_productcats');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        $data = Mage::registry('fileupload_productcats');
        if( isset($data['category_name'])
        &&  $data['category_name']
        )   return $this->__('Edit Category \'%s\'', $this->htmlEscape($data['category_name']));
        else return $this->__('Add Category');
    }
}