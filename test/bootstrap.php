<?php
define('EXT', '.php');
define('APPPATH', __DIR__.'/');
define('SYSPATH', __DIR__.'/system/');

error_reporting(E_ALL | E_STRICT);

// Require Kohana core
require SYSPATH.'classes/kohana/core.php';
require SYSPATH.'classes/kohana.php';

spl_autoload_register(array('Kohana', 'auto_load'));

I18n::lang('en-gb');

Kohana::modules(array('beautiful-view' => __DIR__.'/../'));