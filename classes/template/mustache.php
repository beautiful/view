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
	 * Template directory.
	 */
	public static $dir = 'templates';

	/**
	 * Template_Mustache works with .mustache files
	 */
	public static $ext = 'mustache';
	
	/**
	 * Partials.
	 *
	 * @access  protected
	 */
	protected $_partials = array();

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
	 * Loads a new partial from a path. If the path is empty, the partial will
	 * be removed.
	 *
	 * @param   string  partial name
	 * @param   mixed   partial path, FALSE to remove the partial
	 * @return  Kostache
	 */
	public function partial($name, $path)
	{
		if ( ! $path)
		{
			unset($this->_partials[$name]);
		}
		else
		{
			$final_path = Kohana::find_file(static::$dir, $path, static::$ext);
			
			if ($final_path === FALSE)
			{
				throw new View_Exception(
					'The requested view :path could not be found',
					array(':path' => static::$dir.'/'.$path.'.'.static::$ext));
			}
			
			$this->_partials[$name] = file_get_contents($final_path);
		}

		return $this;
	}
	
	/**
	 * Return a new Mustache for the given template, view, and partials.
	 *
	 * @param   string    template
	 * @param   Kostache  view object
	 * @return  Mustache
	 */
	protected function _stash($template, ViewModel $view)
	{
		return new Beautiful_Mustache(
			$template,
			$view,
			$this->_partials,
			array('charset' => Kohana::$charset));
	}


}