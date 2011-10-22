<?php

class Template_Mock extends Template {

	protected $_dir = 'views';
	
	public function render(ViewModel $viewmodel)
	{
		return 'An example render';
	}

}
