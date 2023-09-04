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

class JshoppingModelFilters extends JModelLegacy{
    	
	public function getFilter_notfinished(){
		$_options_array = JSFactory::getModel("options_array");		
		$nf_option = $_options_array->getNotFinished_Options();				
        return JHTML::_('select.genericlist', $nf_option, 'notfinished','class="form-select" style = "width: 100px;" ','id','name', $notfinished ?? '' );
	}
	
	public function getFilter_year($year){
		$_orders = JSFactory::getModel("orders");		
		$firstYear = $_orders->getMinYear(); 
        $y_option = array();
        $y_option[] = JHTML::_('select.option', 0, " - - - ", 'id', 'name');
        for($y=$firstYear;$y<=date("Y");$y++){
            $y_option[] = JHTML::_('select.option', $y, $y, 'id', 'name');
        }        
        return JHTML::_('select.genericlist', $y_option, 'year', 'class="form-select" style = "width: 80px;" ', 'id', 'name', $year);
	}
	
	public function getFilter_month($month){
		$y_option = array();
        $y_option[] = JHTML::_('select.option', 0, " - - ", 'id', 'name');
        for($y=1;$y<=12;$y++){
            if ($y<10) $y_month = "0".$y; else $y_month = $y;
            $y_option[] = JHTML::_('select.option', $y_month, $y_month, 'id', 'name');
        }        
        return JHTML::_('select.genericlist', $y_option, 'month', 'class="form-select" style = "width: 80px;" ', 'id', 'name', $month);
	}
	
	public function getFilter_day($day){
		$y_option = array();
        $y_option[] = JHTML::_('select.option', 0, " - - ", 'id', 'name');
        for($y=1;$y<=31;$y++){
            if ($y<10) $y_day = "0".$y; else $y_day = $y;
            $y_option[] = JHTML::_('select.option', $y_day, $y_day, 'id', 'name');
        }        
        return JHTML::_('select.genericlist', $y_option, 'day', 'class="form-select" style = "width: 80px;" ', 'id', 'name', $day);
	}
}
?>