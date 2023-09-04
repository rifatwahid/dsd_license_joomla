<?php 

abstract class FrontMambot
{

	public static function getInstance()
	{
		if ( empty(static::$instance) ) {
			static::$instance = new static();
		} 

		return static::$instance;
	}
}