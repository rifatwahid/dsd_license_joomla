<?php
/**
* @version      3.13.2 16.02.2013
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

class JshoppingControllerProduct_images extends JControllerLegacy{
    
    function __construct($config = array()){
        parent::__construct( $config );
    }
	
	function _getLinkForImage($text, $filename) {
		$position = JFactory::getApplication()->input->getInt('position');
		return '<a href="#" onclick="shopProductImage.setImageFromFolder('.$position.', \''.$filename.'\'); return false;">'.$text.'</a>';
	}
    
    function display($cachable = false, $urlparams = false){
		$jshopConfig = JSFactory::getConfig();
        $position = JFactory::getApplication()->input->getInt('position');
		$filter = JFactory::getApplication()->input->getVar('filter');
		$path_length = strlen($jshopConfig->image_product_path) + 1;
        $html = "<div class='images_list_search'><input type='text' id='filter_product_image_name' value='".$filter."'> <input type='button' value='".JText::_('COM_SMARTSHOP_SEARCH')."' onclick='product_images_request(".$position.", \"index.php?option=com_jshopping&controller=product_images&task=display\", document.querySelector(\"#filter_product_image_name\").value)'></div>";
		$html .= '<div class="images_list">';
		foreach( new RecursiveIteratorIterator ( new RecursiveDirectoryIterator ( $jshopConfig->image_product_path ), RecursiveIteratorIterator :: SELF_FIRST ) as $v ) {
			$filename = substr($v, $path_length);            
            if ($filter!='' && !substr_count($filename, $filter)) continue;
			if (file_exists($jshopConfig->image_product_path .'/'.'thumb_'.$filename)){
				$html .= '<div class="one_image">';
				$html .= '<table>';
				$html .= '<tr><td align="center" valign="middle"><div>';
				$html .= $this->_getLinkForImage('<img alt="" title="'.$filename.'" src="'.$jshopConfig->image_product_live_path.'/thumb_'.$filename.'"/>', $filename);
				$html .= '</div></td></tr>';
				$html .= '<tr><td valign="bottom" align="center"><div>';
				$html .= $this->_getLinkForImage($filename, $filename);
				$html .= '</div></td></tr>';
				$html .= '</table>';
				$html .= '</div>';
			}
		}
		$html .= '<div style="clear: both"></div>';
		$html .= '</div>';
        
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeDisplayProductsImagesHTML', array(&$html));
		echo $html;
		die();
	}
}
?>