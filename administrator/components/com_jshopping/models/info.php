<?php
/**
* @version      4.6.1 13.06.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2012. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelInfo extends JModelLegacy{

	private function _remote_file_exists($url){
		return (bool)preg_match('~HTTP/1\.\d\s+200\s+OK~', @current(get_headers($url)));
	}

    public function getUpdateObj($version, $jshopConfig) {
		$result = new stdclass;
		$xml = null;
		$str = file_get_content_curl($jshopConfig->xml_update_path);        
        if ($str){
            $xml = simplexml_load_string($str);
        }elseif (self::_remote_file_exists($jshopConfig->xml_update_path)){
            $xml = simplexml_load_file($jshopConfig->xml_update_path);
        }
        if ($xml){
            if (count($xml->update)) {
                foreach($xml->update as $v){
                    if (((string)$v['version'] == $version) && ((string)$v['newversion'])) {
                        $result->text = JText::sprintf('COM_SMARTSHOP_UPDATE_ARE_AVAILABLE', (string)$v['newversion']);
                        $result->file = (string)$v['file'];
                        $result->link = $jshopConfig->updates_site_path;
                        $result->text2 = JText::sprintf('COM_SMARTSHOP_UPDATE_TO', (string)$v['newversion']);
                        $result->link2 = 'index.php?option=com_jshopping&controller=update&task=update&installtype=url&install_url=sm0:'.$result->file.'&back='.urlencode('index.php?option=com_jshopping&controller=info');
                    }
                }
            }
        }
		return $result;
	}
}
?>