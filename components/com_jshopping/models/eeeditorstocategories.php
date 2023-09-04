<?php 

class ExpresseditorModelEeEditorsToCategories extends JModelLegacy
{
    public function getDataByCategoryId($categoryId) 
    {
        if (empty($categoryId)) {
            return [];
        }

        $db = \JFactory::getDBO();
        $db->setQuery('SELECT * FROM #__ee_editors_to_categories WHERE category_id=' . $categoryId);
        
        return $db->loadObjectList();
    }
}