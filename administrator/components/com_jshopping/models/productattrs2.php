<?php 

class JshoppingModelProductAttrs2 extends JModelLegacy
{
    const TABLE_NAME = '#__jshopping_products_attr2';

    public function deleteByAttrsValuesIds(array $attrsValsIds = [])
    {
        $result = true; 

        if (!empty($attrsValsIds)) {
            $ids = implode(', ', $attrsValsIds);

            if (!empty($ids)) {
                $db = \JFactory::getDBO();
                $query = 'DELETE FROM ' . $db->qn(static::TABLE_NAME) . ' WHERE `attr_value_id` IN(' . $ids . ')';

                $db->setQuery($query);
                $result = $db->execute();
            }
        }

        return $result;
    }

    public function deleteByAttrsIds(array $attrsIds = [])
    {
        $result = true; 

        if (!empty($attrsIds)) {
            $ids = implode(', ', $attrsIds);

            if (!empty($ids)) {
                $db = \JFactory::getDBO();
                $query = 'DELETE FROM ' . $db->qn(static::TABLE_NAME) . ' WHERE `attr_id` IN(' . $ids . ')';

                $db->setQuery($query);
                $result = $db->execute();
            }
        }

        return $result;
    }
}