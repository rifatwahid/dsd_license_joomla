<?php 

$nameLang = 'name_' . JComponentHelper::getParams('com_languages')->get('site');
echo JText::_('COM_SMARTSHOP_NOTICE_EMAIL_LOW_AMOUNT_ATTRS_TEXT');

foreach($this->productAttrsWithLowStock as $productId => $arrayWithObj) {
    foreach( $arrayWithObj as $key => $obj ) {
        $quantity = (int)$obj->count;
        $emailBodyText = $this->prodsModelsWithLowAttrs[$productId]->$nameLang ?: $this->prodsModelsWithLowAttrs[$productId]->{'name_en-GB'};

        if ( !empty($obj->attrsNames) ) {
            $attrsTitles = [];
            foreach($obj->attrsNames as $attrValId => $array) {
                if ( isset($array[$nameLang]) && !empty($array[$nameLang]) ) {
                    $attrsTitles[] = $array[$nameLang];
                }
            }

            if ( !empty($attrsTitles) ) {
                $emailBodyText .= '(' . implode(',', $attrsTitles) . ')';
            }
        }

        echo  $emailBodyText . ' ' . sprintf(JText::_('COM_SMARTSHOP_NOTICE_EMAIL_LOW_AMOUNT_ATTRS_NUMBER_ITEMS_LEFT'), $quantity) . '<br>';                    
    }
}