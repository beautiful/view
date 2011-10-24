<?php

class Template_Mock extends Template {

	public static $dir = 'views';
	
	public function render(ViewModel $viewmodel)
	{
		return 'An example render';
	}

}
