<?php
/**
* @version      4.9.0 18.12.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Uri\Uri;

class jshopContentFront extends jshopBase
{

    public function __construct()
    {
    }


    public function getTextContentByContentName($contentName)
    {
    	$articleText = '';
		$lang = JFactory::getLanguage();
    	$db = \JFactory::getDBO();
    	$langCode1 = $lang->getLocale()['2'];
    	$langCode2 = substr($langCode1,0,strpos($langCode1,'_'));

    	$where="";
		$orderby="";
		$dispatcher = \JFactory::getApplication();
		$dispatcher->triggerEvent('onBeforeGetContentListFront', array(&$where,&$orderby));
    	$sql = 'SELECT `link` FROM `#__jshopping_content` WHERE `content` = ' . $db->q($contentName) . ' AND `lang` = ' . $db->q($langCode2).' '.$where.' '.$orderby; 
		$db->setQuery($sql);
    	$articleId = $db->loadResult(); 
    	$sql = 'SELECT `type` FROM `#__jshopping_content` WHERE `content` = ' . $db->q($contentName) . ' AND `lang` = ' . $db->q($langCode2).' '.$where.' '.$orderby; 
		$db->setQuery($sql);
    	$articleType = $db->loadResult(); 
    	if ( !empty($articleId) ) {
			$article = new stdClass();
			$article->text = '';
			
			$dispatcher = \JFactory::getApplication();
			if($articleType == 1){
				$sql = 'SELECT * FROM `#__content` WHERE `id` = ' . $db->escape($articleId);
				$db->setQuery($sql);
				$article = $db->loadObject(); 
			}elseif(JPluginHelper::isEnabled('content', 'sppagebuilder')){
				if (file_exists(JPATH_ADMINISTRATOR . '/components/com_sppagebuilder/helpers/integrations.php')) JLoader::register('BuilderIntegrationHelper', JPATH_ADMINISTRATOR . '/components/com_sppagebuilder/helpers/integrations.php');
				if (file_exists(JPATH_ADMINISTRATOR . '/components/com_sppagebuilder/helpers/sppagebuilder.php')) JLoader::register('BuilderIntegrationHelper', JPATH_ADMINISTRATOR . '/components/com_sppagebuilder/helpers/sppagebuilder.php');				
				$article->introtext = self::onIntegrationPrepareContent($article->text, 'com_content', 'article', $articleId);				
			}
			
			$articleText = JHtml::_('content.prepare', $article->introtext);  	

    	}else{
			$articleText = '<p>'.JText::_('COM_SMARTSHOP_THANK_YOU_ORDER').'</p>';
		}

    	return $articleText;
    }



	public static function onIntegrationPrepareContent($text, $option, $view, $id = 0) {
		if(!self::getIntegration($option)) return $text;

		$pageName = $view . '-' . $id;

		$page_content = self::getPageContent($option, $view, $id);
		
		if($page_content) {
			jimport('joomla.application.component.helper');
			require_once JPATH_ROOT .'/components/com_sppagebuilder/parser/addon-parser.php';
			$doc = JFactory::getDocument();
			$params = JComponentHelper::getParams('com_sppagebuilder');
			
			if ($params->get('fontawesome',1))
			{
				self::addStylesheet('font-awesome-5.min.css', 'site');
				self::addStylesheet('font-awesome-v4-shims.css', 'site');
			}
			
			if (!$params->get('disableanimatecss',0))
			{
				self::addStylesheet('animate.min.css', 'site');
			}
			
			if (!$params->get('disablecss',0))
			{
				self::addStylesheet('sppagebuilder.css', 'site');
			}

			HTMLHelper::_('jquery.framework');
			HTMLHelper::_('script', 'components/com_sppagebuilder/assets/js/jquery.parallax.js', ['version' => SppagebuilderHelperSite::getVersion(true)] );
			HTMLHelper::_('script', 'components/com_sppagebuilder/assets/js/sppagebuilder.js', ['version' => SppagebuilderHelperSite::getVersion(true)], ['defer' => true]);

			return '<div id="sp-page-builder" class="sp-page-builder sppb-'.$view.'-page-wrapper"><div class="page-content">' . AddonParser::viewAddons(json_decode($page_content->text),0,$pageName) . '</div></div>';
		}

		return $text;
	}

	public static function getPageContent($extension, $extension_view, $view_id = 0) {
		$db = \JFactory::getDBO();
		$query = $db->getQuery(true);
		$query = 'SELECT * FROM `#__sppagebuilder` WHERE '. $db->quoteName('published') . ' = 1 AND ('.$db->quoteName('view_id') . ' = '. $db->quote($view_id).'OR '.$db->quoteName('id') . ' = '. $db->quote($view_id).')' ;
		$db->setQuery($query);
		$result = $db->loadObject();
		if(count((array) $result)) {
			return $result;
		}

		return false;
	}
	
	public function getContentLink($page)
	{
		$db = \JFactory::getDBO();
		$jshopConfig = JSFactory::getConfig();
		$where="";
		$orderby="";
		$dispatcher = \JFactory::getApplication();
		$dispatcher->triggerEvent('onBeforeGetContentListFront', array(&$where,&$orderby));
		$query = "SELECT * FROM `#__jshopping_content` WHERE `content`='" . $page . "' and lang='" . substr($jshopConfig->getLang(), 0, 2) . "' ".$where." ".$orderby;

		$db->setQuery($query);
		$res = $db->loadObject();

        $dispatcher->triggerEvent('onAfterGetContentListFront', array(&$page, &$res));

		if($res->type == 2){
			return JRoute::_('index.php?option=com_sppagebuilder&view=page&id=' . $res->link);
		}
		return JRoute::_('index.php?option=com_content&view=article&id=' . $res->link);
	}
		
	private static function getIntegration($option)
	{
		$group = str_replace('com_', '', $option);
		if (file_exists(JPATH_ADMINISTRATOR . '/components/com_sppagebuilder/helpers/integrations.php')) $integrations = SppagebuilderHelperIntegrations::integrations();
		if (file_exists(JPATH_ADMINISTRATOR . '/components/com_sppagebuilder/helpers/sppagebuilder.php')) $integrations = BuilderIntegrationHelper::getIntegrations();
	
		if(!isset($integrations[$group]))
			{
		  return false;
		}
	
		$integration = $integrations[$group];
		$name = $integration['name'];
		$enabled = 1;
	
		if($enabled)
		{
		  return $integration;
		}
	
		return false;
	}	
	
	public static function addStylesheet($stylesheet, $client = 'admin')
	{
		$doc = JFactory::getDocument();
		$stylesheet_url = JUri::root(true) . ($client == 'admin' ? '/administrator' : '') . '/components/com_sppagebuilder/assets/css/'. $stylesheet;

		
		$doc->addStylesheet($stylesheet_url);
	}
}