<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelFreeAttrsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_free_attr';

    public function getAll(): array
    {
        $lang = JSFactory::getLang();
        $select = [
            '*',
            "`{$lang->get('name')}` as name"
        ];

        return $this->select($select, [], 'ORDER BY `ordering`');
    }

    public function getName(int $attrId): string
    {
        $lang = JSFactory::getLang();
        $select = [
            "`{$lang->get('name')}` as name"
        ];
        $where = [
            "`id` = '{$attrId}'"
        ];

        return $this->select($select, $where, '', false)->name ?: '';
    }

    public function getAllNames(): array
    {
        $result = [];
        $lang = JSFactory::getLang();
        $select = [
            'id',
            "`{$lang->get('name')}` as name"
        ];

        $names = $this->select($select, [], 'ORDER BY `ordering`');

        if (!empty($names)) {
            foreach($names as $name) {
                $result[$name->id] = $name->name;
            }
        }

        return $result;
    }

    public function parseRequireFreeAttrs(array $freeAttrs)
    {
        $result = [];

        if (!empty($freeAttrs)) {
            foreach($freeAttrs as $v) {
                if (is_object($v) && $v->required) {
                    $result[] = $v->id;
                }
            }
        }

        return $result;
    }

    public function fillInputFieldsProperty(array $freeAttrs)
    {
        $result = [];

        if (!empty($freeAttrs)) {
            foreach($freeAttrs as $key => $freeAttrInfo) {
                $value = isset($freeAttrInfo->value) ? $freeAttrInfo->value : '';
				$disabled = $freeAttrInfo->is_fixed ? 'disabled="disabled"' : '';
                $freeAttrInfo->input_field = '<input type="text" class="inputbox freeattr" size="40" name="freeattribut[' . $freeAttrInfo->id . ']" id="freeattribut_' . $freeAttrInfo->id . '" value="' . $value . '" '.$disabled.' />';
                $result[$key] = $freeAttrInfo;
            }
        }

        return $result;
    }

    public function addFreeAttrsToProd(jshopProduct &$product): bool
    {
        $jshopConfig = JSFactory::getConfig();

        $product->freeattributes = null;
        $product->freeattribrequire = 0;

        if ($jshopConfig->admin_show_freeattributes) {
            $product->getListFreeAttributes();
            $product->freeattribrequire = count($product->getRequireFreeAttribute());

            return true;
        }

        return false;
    }
}
