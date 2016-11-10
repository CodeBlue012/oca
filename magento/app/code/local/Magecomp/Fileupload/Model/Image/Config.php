<?php
class Magecomp_Fileupload_Model_Image_Config implements Mage_Media_Model_Image_Config_Interface
{
    public function getBaseMediaUrl()
	{
    	return Mage::getBaseUrl('media') . 'fileupload/files' ;
	}

    public function getBaseMediaPath(){
    	return BP . DS . 'media' . DS . 'fileupload/files' . DS;
	}

    public function getMediaUrl($file)
	{
		$aryfile = explode("/",$file);
	  	return Mage::getBaseUrl('media') . 'fileupload' . DS . 'files' . DS . $file;
    }

    public function getMediaPath($file)
	{
	  	$aryfile = explode("/",$file);
      	return BP . DS . 'media' . DS . 'fileupload' . DS . 'files' . DS . $file;
    }
} 