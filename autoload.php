<?php 

spl_autoload_register();

function formatLocalTime($time)
{
	$time = strtotime($time);
	return date('Y-m-d\Th:i:s', $time);
}

function array_get($array, $key, $default = null)
{
	return isset($array[$key]) ? $array[$key] : $default;
}

 ?>