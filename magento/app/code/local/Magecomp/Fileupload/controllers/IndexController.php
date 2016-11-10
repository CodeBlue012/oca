<?php
class Magecomp_Fileupload_IndexController extends Mage_Core_Controller_Front_Action
{
	public function preDispatch()
    {
		parent::preDispatch();
		
		$login_before_download = Mage::getStoreConfig('fileupload/general/login_before_download');
		$pid     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('fileupload/fileupload')->load($pid);
		$customer_group_id = $model['customer_group_id'];
		
		if($customer_group_id==0){
			$login_before_download = 0;
		}
		
		if($login_before_download){	
			Mage::getSingleton('customer/session')->addError(Mage::helper('fileupload')->__('Please login to download the attachment.'));
			if (!Mage::getSingleton('customer/session')->authenticate($this)) {
				$this->setFlag('', 'no-dispatch', true);
			}
		}
    }
    
    public function indexAction()
    {
		$this->loadLayout();    
		$this->renderLayout();
    }
	
	public function downloadAction(){	
	
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('fileupload/fileupload')->load($id);
		$customer_group_id = $model['customer_group_id'];
		$groupid = Mage::getSingleton('customer/session')->getCustomerGroupId();
		if($customer_group_id=="" || $customer_group_id ==null || $customer_group_id==0){
			
		}else{
			if($customer_group_id!=$groupid){
				$cgroup = Mage::getModel('customer/group')->load($customer_group_id);
				$groupName = $cgroup->getCode();
				Mage::getSingleton('customer/session')->addError(Mage::helper('fileupload')->__('This attachment is for only '.$groupName.' User Group to download'));
				Mage::app()->getFrontController()
			   ->getResponse()
			   ->setRedirect(Mage::getUrl('customer/account'));
			   return;
			}
		}
		Mage::getModel('fileupload/fileupload')->updateCounter($id);
		
		$fileconfig = Mage::getModel('fileupload/image_fileicon');
		$filePath = Mage::getBaseDir('media'). DS . $model['filename'];
		$fileconfig->Fileicon($filePath);
		$fileName = $model['filename'];
		
		$fileType = $fileconfig->getType();
		$fileSize = $fileconfig->getSize();
	
		if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT'])) {
		   ini_set( 'zlib.output_compression','Off' );
   		}
		header("Content-Type: $fileType");
		header("Pragma: public");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Disposition: attachment; filename=$fileName");
		header("Content-Transfer-Encoding: binary");
		header("Content-length: " . filesize($filePath));
		// read file
		readfile($filePath);
		exit();
	}
}
