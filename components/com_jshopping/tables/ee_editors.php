<?php

class ExpresseditorModelEe_Editors extends JModelLegacy{
        
    public function getData($id){
        $db = \JFactory::getDBO();        
        $query = 'SELECT * FROM #__ee_editors '                
                . 'WHERE editor_id='.(int)$id;
        $db->setQuery($query);
        return $db->loadObject();
    }
    
    public function getEditorData($editor_id){
        $db = \JFactory::getDBO();
        $query = 'SELECT * FROM #__ee_editors as ee '
                . 'LEFT JOIN #__ee_editors_types as et ON ee.editor_type=et.id '
                . 'WHERE ee.editor_id='.(int)$editor_id;
        $db->setQuery($query);
        return $db->loadObject();
    }
    
    public function getList($lng = ''){
        $db = \JFactory::getDBO();
        $extfields = '';
        if ($lng){
            $extfields .= ',`editor_title_'.$lng.'` as title ';
        }
        $query = 'SELECT * '.$extfields.' FROM #__ee_editors';
        $db->setQuery($query);
		return $db->loadObjectList();
    }
	public function getList_avalible_editors($c_id,$lng = ''){
		$db = \JFactory::getDBO();
        $extfields = '';
        if ($lng){
            $extfields .= ',`editor_title_'.$lng.'` as title ';
        }
        $query = 'SELECT * '.$extfields.' FROM #__ee_editors ORDER BY `sort` ASC ';
        $db->setQuery($query);
		$editors=$db->loadObjectList();
		$db->setQuery('SELECT * FROM #__ee_editors_to_categories  where category_id='.$c_id);$etocat = $db->loadObject();
		IF ($etocat->avalible_editors<>""){
			$aeditors=explode(';',$etocat->avalible_editors);
			foreach($editors as $key=>$value){
				$exist=false;
				foreach ($aeditors as $ae){
					if ($editors[$key]->editor_id==$ae){$exist=true;}
				}
				if (!$exist){unset($editors[$key]);}
			}
		}
		return $editors;
	}
    public function getEditorsList($editor_id = '',$lng = ''){
        $db = \JFactory::getDBO();

        $query = 'SELECT `assigned_editors` FROM #__ee_editors WHERE `editor_id` = '.(int)$editor_id;
        $db->setQuery($query);
        $editors = $db->loadResult();

        if($editors != ''){
            $query = 'SELECT `editor_title_'.$lng.'` as `title`,`editor_id` as `id` FROM #__ee_editors 
            WHERE `editor_id` in ('.$editors.') 
            ORDER BY FIELD(id, '.$editors.') ';

            $db->setQuery($query);
            $editors = $db->loadObjectList(); 
        }

        return $editors;   

    }
    
}