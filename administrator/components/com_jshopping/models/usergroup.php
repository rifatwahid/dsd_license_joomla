<?php
/**
* @version      4.1.0 31.07.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelUsergroup{ 
	public $usergroup_name = null;
	public $usergroup_id = null;
	
	function __construct($usergroup_name="",$usergroup_id=0){			
			$this->usergroup_name=$usergroup_name;
			$this->usergroup_id=$usergroup_id;
		}
}