<?php

class Session
{
	
	public static function init()
	{
		@session_start();
	}
	public static function set($key, $value)
	{
		//add encyption
		$_SESSION[$key] = $value;
	}
	
	public static function get($key)
	{
		if (isset($_SESSION[$key]))
		return $_SESSION[$key];
	}
	
	public static function destroy()
	{
		unset($_SESSION);
		session_destroy();
	}
	public static function check()
	{
		@session_start();
		if(isset($_SESSION['id'])){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
}