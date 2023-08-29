<?php 

class ExpresseditorModelEeEditorsContent extends JModelLegacy
{
    public function getByEditorId($editorId)
    {
        if (empty($editorId)) {
            return [];
        }

        $db = \JFactory::getDBO();
        $db->setQuery('SELECT * FROM #__ee_editors_content WHERE editor_id = ' . $editorId);

        return $db->loadObjectList();
    }
}