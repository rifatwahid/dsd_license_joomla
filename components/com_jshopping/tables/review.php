<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class jshopReview extends JTableAvto 
{
    public function __construct(&$_db)
    {
        parent::__construct('#__jshopping_products_reviews', 'review_id', $_db);
    }
	
	public function bind($src, $ignore = Array())
	{
		$fields = (parent::getTableFields()) ?: [];		
		foreach ($fields as $key=>$value){
			
			if ((!isset($src[$key]))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))=='TEXT')||(strtoupper(substr($value->Type,0,4))=='VARC')){					
					$src[$key]="";
				}
			}
			
			if ((($src[$key]==""))&&($value->Extra!="auto_increment")){
				if ((strtoupper(substr($value->Type,0,4))!='TEXT')&&(strtoupper(substr($value->Type,0,4))!='VARC')){					
					$src[$key]=0;
				}
			}
						
		}
		return parent::bind($src, $ignore);
	}

    /**
     * @var null|string $type   null - product, other - manufacturer
     */
    public function getAllowReview(?string $type = null,$product_id = 0): int
    {
        return JSFactory::getModel('reviewFront', 'jshop')->isAllowReview($type,$product_id);
    }

    public function getText(): string
    {
                // Not logged in
        return ($this->getAllowReview() == -1) ? JText::_('COM_SMARTSHOP_REVIEW_NOT_LOGGED') : '';
    }
	
    public function check(): int
    {
        if (empty($this->product_id) || empty($this->user_name) || empty($this->user_email) || empty($this->review)) {
            return 0;
        }

        $pid = JSFactory::getModel('ProductsFront')->getByProdId($this->product_id)->product_id ?? null;

        return (empty($pid)) ? 0 : 1;
    }

}
