<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Beautiful Mustache Template
 *
 * @package     Beautiful
 * @subpackage  Beautiful View
 * @category    Template
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Template_Mustache extends Template {

	/**
	 * Default driver works with .php files.
	 *
	 * @var     string
	 * @access  protected
	 * @see     Template::$_extension
	 */
	protected $_extension = 'mustache';
	
	/**
	 * Template directory
	 *
	 * @access  protected
	 */
	protected $_dir = 'templates';

	/**
	 * The rendering method, wooo!
	 *
	 * @param   ViewModel  Data to be passed to template
	 * @return  string     Rendered template
	 */
	public function render(ViewModel $view)
	{
		$template = file_get_contents($this->path());
		return $this->_stash($template, $view)->render();
	}
	
	/**
	 * Return a new Mustache for the given template, view, and partials.
	 *
	 * @param   string    template
	 * @param   Kostache  view object
	 * @param   array     partial templates
	 * @return  Mustache
	 */
	protected function _stash($template, ViewModel $view, array $partials = NULL)
	{
		return new Beautiful_Mustache(
			$template,
			$view,
			$partials,
			array('charset' => Kohana::$charset));
	}


}