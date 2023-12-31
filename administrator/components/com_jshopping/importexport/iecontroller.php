<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package      
* @copyright    Copyright (C) 2010  All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class IeController extends JObject{
    
    function execute( $task ){
        $this->$task();
    }
    
    function save(){
    }
    
    function loadLanguageFile(){
        $adminlang = JFactory::getLanguage();
        $alias = $this->get('alias'); 
        if(file_exists(dirname(__FILE__).'/'.$alias.'/lang/'.$adminlang->getTag().'.php')) {
            require_once (dirname(__FILE__).'/'.$alias.'/lang/'.$adminlang->getTag().'.php');
        } else {
            require_once (dirname(__FILE__).'/'.$alias.'/lang/en-GB.php');
        }
    }
    
}

?>