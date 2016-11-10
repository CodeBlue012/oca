<?php
class Magecomp_Fileupload_Block_Adminhtml_Fileupload_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  	protected function _prepareForm()
  	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('fileupload_form', array('legend'=>Mage::helper('fileupload')->__('File information')));
		
		$fieldset->addField('title', 'text', array(
		  'label'     => Mage::helper('fileupload')->__('Title'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'name'      => 'title',
		));
		
		$fieldset->addField('cat_id', 'select', array(
          'label'     => Mage::helper('fileupload')->__('Category'),
          'name'      => 'cat_id',
		  'type'      => 'text',
          'values'    => Mage::helper('fileupload')->getSelectcat(),
        ));

		$object = Mage::getModel('fileupload/fileupload')->load( $this->getRequest()->getParam('id') );
		$note = false;
		if($object->getFilename()) {
			$File =  Mage::getBaseUrl('media').$object->getFilename();
			
			//Get File Size, Icon, Type
			$fileconfig = Mage::getModel('fileupload/image_fileicon');
			$filePath = Mage::getBaseDir('media'). DS . $object->getFilename();
			$fileconfig->Fileicon($filePath);
			$DownloadURL = $object->getFileIcon().'&nbsp;&nbsp;<a href='.$File.' target="_blank">Download</a>';
		} else {
			$DownloadURL = '';
		}
				
		$fieldset->addField('my_file_uploader', 'file', array(
			'label'        => Mage::helper('fileupload')->__('File'),
			'note'      => $note,
			'name'        => 'my_file_uploader',
			'class'     => (($object->getFilename()) ? '' : 'required-entry'),
			'required'  => (($object->getFilename()) ? false : true),
			'after_element_html' => $DownloadURL,
		 )); 
				
		$fieldset->addField('my_file', 'hidden', array(
			'name'        => 'my_file',
		));
		
		$fieldset->addField('store_id','multiselect',array(
			'name'      => 'stores[]',
			'label'     => Mage::helper('fileupload')->__('Store View'),
			'title'     => Mage::helper('fileupload')->__('Store View'),
			'required'  => true,
			'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true)
		));
		
		$fieldset->addField('status', 'select', array(
		  'label'     => Mage::helper('fileupload')->__('Status'),
		  'name'      => 'status',
		  'values'    => array(
			  array(
				  'value'     => 1,
				  'label'     => Mage::helper('fileupload')->__('Enabled'),
			  ),
		
			  array(
				  'value'     => 2,
				  'label'     => Mage::helper('fileupload')->__('Disabled'),
			  ),
		  ),
		));
		
		//fetch all user groups
		$customer_group = new Mage_Customer_Model_Group();
		$groups_array = array();
		$allGroups  = $customer_group->getCollection()->toOptionHash();
		foreach($allGroups as $key=>$allGroup){
			  $groups_array[] = array('value'=>$key, 'label'=>$allGroup,);
		}
		  
		$fieldset->addField('customer_group_id', 'select', array(
		  'label'     => Mage::helper('fileupload')->__('Customer Group'),
		  'name'      => 'customer_group_id',
		  'values'    => $groups_array,
		  'after_element_html' => '<p class="nm"><small>' . Mage::helper('fileupload')->__('(This option will override the configuration settings)') . '</small></p>'
		));
		
		$fieldset->addField('limit_downloads', 'text', array(
		  'label'     => Mage::helper('fileupload')->__('Limit Downloads'),
		  'name'      => 'limit_downloads',
		  'after_element_html' => '<p class="nm"><small>' . Mage::helper('fileupload')->__('(Enter number of downloads for this attachment. If empty then unlimited.)') . '</small></p>'
		));
		
		$fieldset->addField('content', 'editor', array(
		  'name'      => 'content',
		  'label'     => Mage::helper('fileupload')->__('Description'),
		  'title'     => Mage::helper('fileupload')->__('Description'),
		  'style'     => 'width:400px; height:200px;',
		  'wysiwyg'   => false,
		  'required'  => false,
		));
		
		if ( Mage::getSingleton('adminhtml/session')->getFileuploadData() )
		{
		  $form->setValues(Mage::getSingleton('adminhtml/session')->getFileuploadData());
		  Mage::getSingleton('adminhtml/session')->setFileuploadData(null);
		} elseif ( Mage::registry('fileupload_data') ) {
		  $form->setValues(Mage::registry('fileupload_data')->getData());
		}
		return parent::_prepareForm();
  	}
}