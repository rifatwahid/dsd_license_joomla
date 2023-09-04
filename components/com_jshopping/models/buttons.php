<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_ROOT . '/components/com_jshopping/lib/Mambots/exclude_buttons_for_attribute/exclude_buttons_for_attribute.php';

class JshoppingModelButtons extends jshopBase
{    

	private $show_buttons = array ('cart'=>'0','upload'=>'0','editor'=>'0');
	
	public function excludeButtonsForAttribute($attributeValues, $attributeSelected){
		$this->show_buttons=ExcludeButtonsForAttribute::getInstance()->onBuildSelectAttribute($attributeValues, $attributeSelected);		
		return $this->show_buttons;
	}
	public function excludeButtonsForAttributeCart(&$cart){
		$this->show_buttons=ExcludeButtonsForAttribute::getInstance()->onBuildSelectAttributeCart($cart);		
		return $this->show_buttons;
	}
	public function getButtonCartExlude(){
		return $this->show_buttons['cart'];
	}
	public function getButtonUploadExlude(){
		return $this->show_buttons['upload'];
	}
	public function getButtonEditorExlude(){
		return $this->show_buttons['editor'];
	}
}