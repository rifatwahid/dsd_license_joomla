<?php 

defined('_JEXEC') or die('Restricted access');

class JshoppingModelLanguagesFront extends jshopBase
{
    public const TABLE_NAME = '#__languages';

    public function getAllLanguages(int $publish = 1): array
    {
        $sortLanguages = [];

        $select = ['*', '`title` as `name`', '`lang_code` as `language`', '`lang_id` as `id`', '`published` as `publish`'];
        $where = $publish ? ["`published`='1'"] : []; 
        $allLanguages = $this->select($select, $where, 'ORDER BY `ordering`');
        
        if (!empty($allLanguages)) {
            $jshopConfig = JSFactory::getConfig();

            foreach($allLanguages as $k => $lang) {
                $allLanguages[$k]->lang = substr($lang->language, 0, 2);
                
                if ($jshopConfig->getLang() == $lang->language) {
                    $sortLanguages[] = $allLanguages[$k];
                }
            }
    
            foreach($allLanguages as $k => $lang) {
                if (isset($sortLanguages['0']) && $sortLanguages['0']->language == $lang->language) {
                    continue;
                }
    
                $sortLanguages[] = $lang;            
            }
    
            unset($allLanguages);
        }

        return $sortLanguages;
    }
}