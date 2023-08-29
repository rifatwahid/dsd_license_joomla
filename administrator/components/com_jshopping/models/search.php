<?php
/**
* @version      2.9.0 31.07.2010
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelSearch extends JModelLegacy{ 

	public function getSearchResults(){
		$jshopConfig = JSFactory::getConfig();
		$language=$jshopConfig->adminLanguage;
		
		$keywords=$this->getKeywordsArray();		
		$result=array();
		foreach ($keywords as $keyword){
			if ($keyword!=""){
				$res=$this->searchInDB($keyword,$language);
				foreach ($res as $k=>$v){
					if (!in_array($v,$result)){
						$result[]=$v;					
					}
				}			
			}
		}		
		return $result;
	}	
	
	private function searchInDB($keyword,$language){		
		$keyword = $keyword ?? '';
		$db = \JFactory::getDBO();         				
        $query = "SELECT * FROM `#__jshopping_search` WHERE `keyword_".$language."` LIKE '%$keyword%'";        		
        $db->setQuery($query);
		$res=$db->loadObjectList();
		$result_array=array();
		foreach ($res as $k=>$v){
			$result=array();
			$result['title']=strtoupper($v->title);
			$result['links']=$v->links;
			$result_array[]=$result;
		}
        return $result_array;
	}
	
    private function getKeywordsArray(){
		$jshopConfig = JSFactory::getConfig();  
		$folder=$jshopConfig->admin_path."/controllers/";
		$text_search = strtoupper(trim (JFactory::getApplication()->input->getVar('text_search')));
		$text_search=str_replace('  ',' ',$text_search);		
		$keywords_array=explode(',',$text_search);
		return $keywords_array;
	}
	public function keywordsToUpper(){
		$db = \JFactory::getDBO();         
		$query = "SELECT * FROM `#__jshopping_search` ";        
        $db->setQuery($query);
        $keywords_rows=$db->loadObjectList();
		foreach ($keywords_rows as $keyword_row){
			$keyword_lang='keyword_de-DE';
			$query = 'UPDATE #__jshopping_search set `'.$keyword_lang.'`="'.strtoupper($keyword_row->$keyword_lang).'" where id=' . $keyword_row->id;
			//echo $query;die();
			$db->setQuery($query);
			$db->execute();			
		}
	}
	
	public function getAllLanguageVariables(){
		$jshopConfig = JSFactory::getConfig();  
		$en=file_get_contents("language/en-GB/en-GB.com_jshopping.ini");
		$folder=$jshopConfig->admin_path."/controllers/";
		$db = \JFactory::getDBO();         
        $query = "SELECT * FROM `#__jshopping_search`";        
        $db->setQuery($query);
        $rows=$db->loadObjectList();
		foreach ($rows as $row){
			$keywords=explode(',',$row->keyword);
			$keyword_line="";
			foreach ($keywords as $keyword){
				$keyword='"'.$keyword.'"';
				echo "<br>".$keyword.": ";
				$index=strripos($en,trim($keyword));
				if ($index>0){				
					$found2=substr($en,$index+1,strpos(substr($en,$index+1,200),'"'));					
					
					$found=substr($en,0,$index);
					$pos=strrpos($found,'"');
					
					if ($pos>0){						
						$found=substr($found,$pos,$index);
						$found=str_replace('"','',$found);
						$found=str_replace('=','',$found);
						$found=str_replace(' ','',$found);
						echo $found2." (".$found.")";
						if ($keyword_line!=""){
							$keyword_line=$keyword_line.",".$found;
						}else{
							$keyword_line=$found;
						}
					
					}				
				}
			}
			if ($keyword_line!=""){
				$query = "UPDATE #__jshopping_search set `lang_variable` ='".$keyword_line."' where id=" . $row->id;
				$db->setQuery($query);
				$db->execute();			
			}
		}	
	}
	
	private function searchTranslate($en,$keyword){
		$index=stripos($en,trim($keyword));
		$end_part=substr($en,$index,strlen($en));		
		if ($index>0){			
			$end_part=substr($end_part,stripos($end_part,'"')+1,strlen($end_part));			
			$end_part=substr($end_part,0,stripos($end_part,'"'));
			return $end_part;
		}
	}
	
	public function reloadKewordsForLanguages(){
		$jshopConfig = JSFactory::getConfig();
		$language=$jshopConfig->adminLanguage;
		
		$language='fr-FR';
		$pathToLangFile = JPATH_ROOT . '/language/' . $language . '/' . $language . '.com_jshopping.ini';
		$en = file_exists($pathToLangFile) ? file_get_contents($pathToLangFile): '';			
		$db = \JFactory::getDBO();         
        $query = "SELECT * FROM `#__jshopping_search`";        
        $db->setQuery($query);
        $rows = $db->loadObjectList();
		foreach ($rows as $row){
			$keywords=str_replace(',',' ',$row->lang_variable);
			$keywords=explode(' ',$keywords);
			$translated_line="";
			foreach ($keywords as $keyword){
				$from_lang_file = $this->searchTranslate($en, $keyword) ?: '';
				if ($translated_line == ''){
					$translated_line = $from_lang_file;
				}else{
					$translated_line = $translated_line . ' ' . $from_lang_file;
				}
			}
			
			if ($translated_line!=""){
				$translated_line=stripslashes($translated_line);
				$translated_line=str_replace("'",' ',$translated_line);								
				$translated_line=str_replace("&#39;",'`',$translated_line);												
				$translated_line=str_replace("&#39",'`',$translated_line);												
				echo "<br>".$row->lang_variable."=".$translated_line;
				$query = 'UPDATE #__jshopping_search set `keyword_'.$language.'` ="'.($translated_line).'" where id=' . $row->id;
				$db->setQuery($query);
				$db->execute();			
			}
		}
		
		echo "<pre>";		
	}
	
	public function scanLangsFolders(){
		$dirs=scandir("language/");
		$language_folders=array();
		foreach ($dirs as $key=>$dir){
			if (strpos($dir,'-')>0) {
				$language_folders[]=$dir;
			}
		}
		return $language_folders;
	}
	
	public function getAllLanguageVariblesArray()
	{
		$language = 'en-GB';
		$languages = $this->scanLangsFolders();
		foreach ($languages as $language) {		
			$langFile = JPATH_ROOT . '/language/' . $language . '/' . $language . '.com_jshopping.ini';	

			if (file_exists($langFile)) {
			$lang_file[$language]=file_get_contents($langFile);			
			$lang_file_lines[$language]=explode('
',$lang_file[$language]);
			}
		}
		$lang_variables = [];
		foreach ($lang_file_lines['en-GB'] as $lang_file_line){			
			if (!stripos('=',$lang_file_line)>0){
				$line_parts=explode('=',$lang_file_line);				
				//$variable['variable']=trim($line_parts[0]);
				$variable[$language]=trim($line_parts[1] ?? '');
				$lang_variables[trim($line_parts[0])]=$variable;
			}
		}

		foreach ($languages as $language) {
			if (isset($lang_file_lines[$language])) {
				foreach ($lang_file_lines[$language] as $lang_file_line){	
					if (!stripos('=',$lang_file_line)>0){
						$line_parts=explode('=',$lang_file_line);		
						$translate=trim($line_parts[1] ?? '');
						$translate=str_replace('"',"",$translate);
						$translate=str_replace("'","",$translate);
						$translate=str_replace("<a href=","",$translate);
						$translate=str_replace("<","",$translate);
						$translate=str_replace("/>","",$translate);
						$translate=str_replace(">","",$translate);
						$lang_variables[trim($line_parts[0])][$language]=$translate;
					}
				}
			}
		}

		return $lang_variables;		
	}
	
	public function getAllLinksArrayByTemplates(){		
		$jshopConfig = JSFactory::getConfig();
		$template_paths=scandir($jshopConfig->template_path.'/'.$jshopConfig->template);
		$current_template_folders=array();
		foreach ($template_paths as $v){
			if ($v[0]!='.') {
				$templates=scandir($jshopConfig->template_path.'/'.$jshopConfig->template.'/'.$v);
				$current_template_folders[$v]=$templates;
			}
		}
	}
	
	public function scanLinks($search,$result){
		$jshopConfig = JSFactory::getConfig();		
		$language=$jshopConfig->adminLanguage;
		
		$files=scandir($jshopConfig->admin_path.'/controllers');
		$controllers=array();
		foreach ($files as $file){
			if ($file[0]!='.') {								
				$functions=explode('function',file_get_contents($jshopConfig->admin_path.'/controllers/'.$file));
				$fl=explode('.php',$file);
				foreach ($functions as $k=>$function){
					if ($k>0){
						$function_name=explode('(',$function);
						$view=explode('->getView("',$function);
						$layout=explode('->setLayout("',$function);
						$params=$parts ?? [];
						$params['view']=trim(substr($view[1] ?? '',0,strpos($view[1] ?? '','"')));
						$params['layout']=trim(substr($layout[1] ?? '',0,strpos($layout[1] ?? '','"')));						
						if (($params['view']!="")AND($params['layout']!="")) $controllers[$fl[0]][trim($function_name[0])]=$params;					
					}
					
				}				
			}
		}				
		//PREPEAR ARRAY FOR SEARCH
		$keywords_arrays=$this->scanTemplates($controllers);
		$this->checkLinks($keywords_arrays);				
		//PREPEAR SEARCH WORDS
		$keywords=$this->getKeywordsArray();				
		foreach ($keywords as $keyword){
			if ($keyword!=""){				
				foreach ($keywords_arrays as $keywords_array){
					if (stripos(" ".$keywords_array['keywords'],$keyword)>0){	
						$is_new=true;
						foreach ($result as $res){							
							if ($res['links']==$keywords_array['links']){$is_new=false;}							
						}
						if ($is_new){$result[]=$keywords_array;}
					}
				}
			}
		}		//die();
		return $result;
	}
	
	public function scanTemplates(&$controllers){
		$translations=$this->getAllLanguageVariblesArray();
		$jshopConfig = JSFactory::getConfig();
		$language=$jshopConfig->adminLanguage;		
		$jshopConfig = JSFactory::getConfig();		
		//CONTROLLERS LIST		
		foreach($controllers as $k=>$controller){
			//TASKS LIST
			foreach ($controller as $task=>$view){
				$lang_variables=array();
				$controllers[$k][$task]['links']='index.php?option=com_jshopping&controller='.$k.'&task='.$task;
				$template_file=$jshopConfig->admin_path.'views/'.$view['view'].'/tmpl/'.$view['layout'].'.php';				
				$template_file = (file_exists($template_file)) ? str_replace('"',"'",file_get_contents($template_file)) : '';
				$template=explode("JText::_(",$template_file);
				$controllers[$k][$task]['keywords']="";
				foreach ($template as $parts){
					if (isset($parts[0]) && $parts[0]=="'"){
						$variable=explode("'",$parts)[1];
						$controllers[$k][$task]['lang_variables'][]=$variable;
						$controllers[$k][$task]['keywords'].= $translations[$variable][$language] ?? '';
						$controllers[$k][$task]['keywords'].= ' ';
					}
				}
				if ($controllers[$k][$task]['keywords']!=""){
					$keywords['title']=strtoupper($k." / ".$view['view']." / ".$view['layout']);
					$keywords['links']=$controllers[$k][$task]['links'];
					$keywords['keywords']=strtoupper($controllers[$k][$task]['keywords']);
					$keywords_array[]=$keywords;
				}				
			}
		}
		return $keywords_array;		
	}
	
	//SEARCH BY PAGES CONTENT
	public function searchInContent(){
		$jshopConfig = JSFactory::getConfig();
		$language=$jshopConfig->adminLanguage;
		
		$jshopConfig = JSFactory::getConfig();		
		$keywords=$this->getKeywordsArray();
		
		$files=scandir($jshopConfig->admin_path.'/controllers');
		$controllers=array();
		$links=array();
		//echo "<pre>";print_r($files);die();
		foreach ($files as $file){
			if ($file[0]!='.') {								
				$functions=explode('function',file_get_contents($jshopConfig->admin_path.'/controllers/'.$file));
				$fl=explode('.php',$file);				
				foreach ($functions as $k=>$function){
					if ($k>0){
						$function_name=explode('(',$function);						
						$link['content']=file_get_contents($jshopConfig->live_admin_path.'index.php?option=com_jshopping&controlle='.$fl[0].'&task='.trim($function_name[0]));						
						$links[]='index.php?option=com_jshopping&controlle='.$fl[0].'&task='.trim($function_name[0]);						
						
					}					
				}				
			}
		}		
	}
	
	public function checkLinks(&$keywords_arrays){
		$db = \JFactory::getDBO();         
		$query = "SELECT * FROM `#__jshopping_search_blocklinks`";        
        $db->setQuery($query);
        $blocklinks=$db->loadObjectList();
		foreach ($keywords_arrays as $k=>$keywords_array){
			$block=false;
			foreach ($blocklinks as $blocklink){
				if ($keywords_array['links']==$blocklink->link) $block=true;
			}
			if ($block) unset($keywords_arrays[$k]);
		}

	}
	
	public function getResultInCurrentLanguage(&$rows){
		//str_replace(' / Media / ',' / COM_SMARTSHOP_IMAGE_VIDEO_PARAMETERS / ',$res->title);
		//str_replace(' / Shop info / ',' / COM_SMARTSHOP_STORE_INFO / ',$res->title);
		//str_replace(' / Other config / ',' / COM_SMARTSHOP_OC / ',$res->title);
		//str_replace(' / PDF hub / ',' / COM_SMARTSHOP_CONFIGURATION_PDF / ',$res->title);
		/*
		$db = \JFactory::getDBO();         
		$query = "SELECT * FROM `#__jshopping_search`";        
        $db->setQuery($query);
        $ress=$db->loadObjectList();
		foreach ($ress as $res){
			$res->title=str_replace(' / Currency / ',' / COM_SMARTSHOP_PANEL_CURRENCIES / ',$res->title);
			$query = 'UPDATE #__jshopping_search set `title`="'.$res->title.'" where id=' .$res->id;			
			$db->setQuery($query);
			$db->execute();			
		}
		/**/
		
		
		foreach ($rows as $k=>$row){
			$names=explode('/',$row['title']);
			foreach ($names as $kk=>$name){
				if ($name!=""){
					$name=str_replace('COM_SMARTSHOP_','',$name);
					$name=str_replace('_',' ',$name);
					$name=str_replace(' ',' ',$name);
					$name=trim($name);
					$name=str_replace(' ','_',$name);					
					$names[$kk]=JText::_('COM_SMARTSHOP_'.$name);
				}				
			}
			$rows[$k]['title']=implode(' / ',$names);
		}
	}
}
?>