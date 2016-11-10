<?php
class Magecomp_Fileupload_Model_Image_Fileicon extends Mage_Core_Helper_Abstract {

	protected $filename;
	protected $size;
	protected $url = 'fileupload/icons/';
	
	var $icons = array(
	
	// Microsoft Office
	'docx' => array('docx', 'Word Document'), //done
	'doc' => array('docx', 'Word Document'), //done
	'xls' => array('xls', 'Excel Spreadsheet'), //done
	'xls' => array('xls', 'Excel Spreadsheet'), //done
	'ppt' => array('ppt', 'PowerPoint Presentation'), //done
	'pptx' => array('ppt', 'PowerPoint Presentation'), //done
	'pps' => array('ppt', 'PowerPoint Presentation'), //done
	'pot' => array('ppt', 'PowerPoint Presentation'), //done

	'mdb' => array('access', 'Access Database'), //done
	'vsd' => array('visio', 'Visio Document'), //done
//	'xxxx' => array('project', 'Project Document'), 	// dont remember type...
	'rtf' => array('rtf', 'RTF File'),

	// XML
	'htm' => array('htm', 'HTML Document'), //done
	'html' => array('htm', 'HTML Document'), //done
	'xml' => array('htm', 'XML Document'),

	 // Images
	'jpg' => array('jpg', 'JPEG Image'),
	'jpe' => array('jpg', 'JPEG Image'),
	'jpeg' => array('jpg', 'JPEG Image'),
	'gif' => array('gif', 'GIF Image'),
	'bmp' => array('bmp', 'Windows Bitmap Image'),
	'png' => array('png', 'PNG Image'),
	'tif' => array('tiff', 'TIFF Image'),
	'tiff' => array('tiff', 'TIFF Image'),
	
	// Audio
	'mp3' => array('mp3', 'MP3 Audio'), //done
	'wma' => array('wma', 'WMA Audio'), //done
	'mid' => array('midi', 'MIDI Sequence'), //done
	'midi' => array('midi', 'MIDI Sequence'), //done
	'rmi' => array('midi', 'MIDI Sequence'), //done
	'au' => array('audio', 'AU Sound'), //done
	'snd' => array('audio', 'AU Sound'), //done

	// Video
	'mpeg' => array('mpeg', 'MPEG Video'), //done
	'mpg' => array('mpeg', 'MPEG Video'), //done
	'mpe' => array('mpeg', 'MPEG Video'), //done
	'wmv' => array('video', 'Windows Media File'), //done
	'avi' => array('video', 'AVI Video'), //done
	
	// Archives
	'zip' => array('zip', 'ZIP Archive'), //done
	'rar' => array('rar', 'RAR Archive'), //done
	'cab' => array('zip', 'CAB Archive'), //done
	'gz' => array('zip', 'GZIP Archive'), //done
	'tar' => array('zip', 'TAR Archive'), //done
	'zip' => array('zip', 'ZIP Archive'), //done
	
	// OpenOffice
	'sdw' => array('oo-write', 'OpenOffice Writer document'), //done
	'sda' => array('oo-write', 'OpenOffice Draw document'), //done
	'sdc' => array('oo-write', 'OpenOffice Calc spreadsheet'), //done
	'sdd' => array('oo-write', 'OpenOffice Impress presentation'), //done
	'sdp' => array('oo-write', 'OpenOffice Impress presentation'), //done

	// Others
	'txt' => array('txt', 'Text Document'),	//done
	'js' => array('js', 'Javascript Document'), //done
	'dll' => array('htm', 'Binary File'), //done
	'pdf' => array('pdf', 'Adobe Acrobat Document'), //done
	'php' => array('htm', 'PHP Script'),//done
	'ps' => array('htm', 'Postscript File'),//done
	'dvi' => array('htm', 'DVI File'),//done
	'swf' => array('swf', 'Flash'),//done
	'chm' => array('htm', 'Compiled HTML Help'),//done

	//Photoshop
	'psd' => array('psd', 'Photoshop File'),

	// Unkown
	'default' => array('txt', 'Unkown Document'),
	);

	public function Fileicon($filename)
	{
		$this -> filename = $filename;
		$this -> size = filesize($this -> filename);
	}
	
	public function setIconUrl($url)
	{
		$this -> url = Mage::getBaseUrl('js').$url;
	}	

	public function getSize()
	{
        return $this -> evalSize($this -> size);
	}
	
	public function getTime()
	{
		return fileatime($this -> filename);
	}

	public function getName()
	{
		return $this -> filename;
	}

	public function getOwner(){
		return fileowner($this -> filename);
	}

	public function getGroup(){
		return filegroup($this -> filename);
	}

	public function getType()
	{
		$f_name = $this->filename;
		$path_parts = pathinfo($f_name);
		$file_ext = $path_parts['extension'];
		
		if(strlen($file_ext)>0){
			return $file_ext;
		}else{
			return false;
		}
	}
	
	public function evalSize($size) {
		if ($size >= 1073741824) return round($size / 1073741824 * 100) / 100 . " GB";
		elseif ($size >= 1048576) return round($size / 1048576 * 100) / 100 . " MB";
		elseif ($size >= 1024) return round($size / 1024 * 100) / 100 . " KB";
		else return $size . " BYTE";	
	}
	
	public function getIcon() {
		$extension = $this -> getType();

		if (key_exists($extension, $this -> icons)) return $this -> icons[$extension];
		else return $this -> icons['default'];
	}
	
	public function displayIcon() {
		$array = $this -> getIcon();
		return '<img src="' . Mage::getBaseUrl('js').$this -> url . $array[0] . '.gif" alt="' . $array[1] . '" />';
	}
}
?>