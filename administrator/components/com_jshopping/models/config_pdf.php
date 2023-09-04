<?php
/**
* @version      3.12.0 10.11.2012
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelConfig_pdf extends JModelLegacy{
    	
	public function getHeaderFooterImagesNamesArray(){		
		return array('imageheader'=>'header.jpg', 'imagefooter'=>'footer.jpg');		
	}
		
	public function checkNeedRemoveImages(){
		$jshopConfig = JSFactory::getConfig();
		$remove = JFactory::getApplication()->input->getVar('remove');
		$extconf = $this->getHeaderFooterImagesNamesArray();
		if ($remove=='header'){
			unlink($jshopConfig->path."images/".$extconf['imageheader']);
			//copy($jshopConfig->path."images/header_default.jpg",$jshopConfig->path."images/".$extconf['imageheader']);			
		}
		if ($remove=='footer'){
			unlink($jshopConfig->path."images/".$extconf['imagefooter']);
			//copy($jshopConfig->path."images/footer_default.jpg",$jshopConfig->path."images/".$extconf['imagefooter']);	
		}		
	}
	
	public function getHeaderImage()
	{
		return $this->getPdfImage();
	}
	
	public function getFooterImage(){

		return $this->getPdfImage('imagefooter');
	}
	
	protected function getPdfImage($type = 'imageheader')
	{
		$image = '';
		$jshopConfig = JSFactory::getConfig();
		$extconf = $this->getHeaderFooterImagesNamesArray();
		$pathToFile = $jshopConfig->path . 'images/' . $extconf[$type];

		if (file_exists($pathToFile)) {
			$size = getimagesize($pathToFile);

			if ($size[0] > 1 || $size[1] > 1) {
				$image = $jshopConfig->live_path . 'images/' . $extconf[$type];
			}
		}

		return $image;
	}
	
}