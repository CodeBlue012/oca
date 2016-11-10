<?php
class Magecomp_Fileupload_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_PATH_LIST_PRODUCT_PAGE_ATTACHMENT_HEADING	= 'fileupload/fileupload/product_attachment_heading';
	const XML_PATH_LIST_CMS_PAGE_ATTACHMENT_HEADING	= 'fileupload/cmspagesattachments/cms_page_attachment_heading';
	
    protected static $_URL_ENCODED_CHARS = array(
            ' ', '+', '(', ')', ';', ':', '@', '&', '`', '\'',
            '=', '!', '$', ',', '/', '?', '#', '[', ']', '%',
        );

	public function getProductPageAttachmentHeading()
	{
		return Mage::getStoreConfig(self::XML_PATH_LIST_PRODUCT_PAGE_ATTACHMENT_HEADING);
	}
	
	public function getCMSPageAttachmentHeading()
	{
		return Mage::getStoreConfig(self::XML_PATH_LIST_CMS_PAGE_ATTACHMENT_HEADING);
	}
	
	public static function nameToUrlKey($name)
    {
        $name = trim($name);

        $name = str_replace(self::$_URL_ENCODED_CHARS, '_', $name);

        do {
            $name = $newStr = str_replace('__', '_', $name, $count);
        } while($count);

        return $name;
    }
	
	public function getCatData($pid=0){
		$out = array();
         $collection = Mage::getModel('fileupload/productcats')->getCollection()
			->addOrder('category_name', 'ASC');

		foreach ($collection as $item){
			$out[] = $item->getData();
		}
		
		return $out;
	}
	
	public function getSelectcat(){
		$this->drawSelect(0);				
        foreach ($this->outtree['value'] as $k => $v){
        	$out[] = array('value'=>$v, 'label'=>$this->outtree['label'][$k]);
        }
		return $out;
	}


	public function drawSelect($pid=0){
		$items = $this->getCatData($pid);
		if(count($items) > 0 ){
			$this->outtree['value'][] = $item[0];
			$this->outtree['label'][] = 'Select Category';
			foreach ($items as $item){
				$this->outtree['value'][] = $item['category_id'];
				$this->outtree['label'][] = $item['category_name'];
			}
		} 
		return;
	}
}