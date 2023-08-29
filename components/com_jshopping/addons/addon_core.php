<?php
/**
* @version      4.11.0 01.09.2015
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

abstract class AddonCore
{
    
    protected $addon_params;
    protected $addon_alias;
	protected $addon_id;
    
    public function __construct()
    {
        if (empty($this->addon_alias)) {
            throw new Exception('addon_alias empty');
        }
    }
    
    public function getAddonAlias()
    {
        return $this->addon_alias;
    }

    public function getAddonParams()
    {
        if (empty($this->addon_params)) {
            $addonTable = JSFactory::getTable('addon', 'jshop');
            $addonTable->loadAlias($this->addon_alias);
            $this->addon_params = $addonTable->getParams();        
        }

        return $this->addon_params;
    }
    
    public function getAddonId()
    {
        if (empty($this->addon_id)) {
            $addonTable = JSFactory::getTable('addon', 'jshop');
            $addonTable->loadAlias($this->addon_alias);
            $this->addon_id = $addonTable->id;        
        }

        return $this->addon_id;
    }
    
    public function getView($layout = '')
    {
        include_once(JPATH_COMPONENT_SITE . '/views/addons/view.html.php');

        $addon_tmpl_path = JPATH_COMPONENT_SITE . '/templates/addons/' . $this->addon_alias;
        $view = new JshoppingViewAddons([
            'template_path' => $addon_tmpl_path
        ]);

        if (!empty($layout)) {
            $view->setLayout($layout);
        }   

        $view->set('addon_path_images', $this->getPathImages());

        return $view;
    }
    
    public function loadLanguage()
    {
        JSFactory::loadExtLanguageFile($this->addon_alias);
    }
    
    public function loadCss()
    {
        $document = JFactory::getDocument();        
        $document->addStyleSheet(JURI::root() . 'components/com_jshopping/css/addons/' . $this->addon_alias . '.css');
    }
    
    public function loadJs($extname = '')
    {
        $document = JFactory::getDocument();        
        $document->addScript(JURI::root() . 'components/com_jshopping/js/addons/' . $this->addon_alias . $extname . '.js');
    }
    
    public function getPathImages()
    {
        return JURI::root() . 'components/com_jshopping/images/' . $this->addon_alias;
    }
    
}