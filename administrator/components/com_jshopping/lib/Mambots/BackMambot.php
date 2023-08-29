<?php 

abstract class BackMambot
{

	public static function getInstance()
	{
		if ( empty(static::$instance) ) {
			static::$instance = new static();
		} 

		return static::$instance;
	}
}