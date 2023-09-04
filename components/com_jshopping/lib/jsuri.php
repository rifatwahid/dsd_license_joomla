<?php
/**
* @version      4.11.6 24.12.2015
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die();

class JSUri extends JUri
{
	public static function isInternal($url)
	{
		$uri = static::getInstance($url);
		$base = $uri->toString(['scheme', 'host', 'port', 'path']);
		$host = $uri->toString(['scheme', 'host', 'port']);

		if (stripos($base, static::base()) !== 0 && !empty($host)) {
			return false;
		}

		return true;
	}

	/**
	 * @param string $url
	 */
	public static function isUrl(string $url): bool
	{
		return (bool)preg_match('~(https:\/\/|http:\/\/|www\.){1}.+(\..{1,}\/)~', $url);
	}
}