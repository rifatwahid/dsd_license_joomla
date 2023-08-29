<?php

defined('_JEXEC') or die('Restricted access');

if (!class_exists('JshoppingModelBathProductEdit')) {
    require __DIR__ . '/bathproductedit.php';
}

class JshoppingModelDescriptionBatchProductEdit extends JshoppingModelBathProductEdit
{   
    public function resolveActionOfProduct(jshopProduct &$product, $post, int $actionId)
    {
        $isReplaceAction = ($actionId == static::CODES['REPLACE']);

        if ($isReplaceAction) {
            JSFactory::getModel('Languages')->productSave_setPostValues($post);

            foreach ($post as $name => $value) {
                if ($this->isFromDescriptionSection($name)) {
                    $product->{$name} = $value;
                }
            }
        }   
    }

    protected function isFromDescriptionSection(string $fieldName): bool
    {
        $mathTo = [
            'short_description_',
            'description_'
        ];

        foreach ($mathTo as $name) {
            $pattern = '~^' . $name . '[a-zA-Z]{2}-[a-zA-Z]{2}~';
            $isMatch = preg_match($pattern, $fieldName);

            if ($isMatch === 1) {
                return true;
            }
        }

        
        return false;
    }
}