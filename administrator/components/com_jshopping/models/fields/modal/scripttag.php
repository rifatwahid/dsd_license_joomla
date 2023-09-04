<?php

defined('JPATH_BASE') or die;

class JFormFieldModal_Scripttag extends JFormField
{	
	protected $type = 'Modal_Scripttag';

	protected function getInput()
	{		
        $url = (string)$this->element['url'];
        $isForJ4 = (bool)$this->element['isForJ4'];

        if (!$isForJ4 || ($isForJ4 && (Joomla\CMS\Version::MAJOR_VERSION == 4))) {
            if (!empty($url)) {
                $doc = JFactory::getDocument();
                $doc->addScript($url);
            }
        }
	}	
}