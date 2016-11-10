<?php
class Magecomp_Fileupload_Model_Image_Configimg implements Mage_Media_Model_Image_Config_Interface
{
    public function getBaseMediaUrl()
	{
    	return Mage::getBaseUrl('media') . 'fileupload/images' ;
	}

    public function getBaseMediaPath()
	{
    	return BP . DS . 'media' . DS . 'fileupload/images' . DS;
	}

	public function getMediaUrl($file)
	{
		$aryfile = explode("/",$file);
	  	return Mage::getBaseUrl('media') . 'fileupload/images' . DS . $file;
    }

    public function getMediaPath($file)
	{
		$aryfile = explode("/",$file);
      	return BP . DS . 'media' . DS . 'fileupload/images' . DS . $file;
    }
} 