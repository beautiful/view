<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Beautiful JSON Template
 *
 * @package     Beautiful
 * @subpackage  Beautiful View
 * @category    Template
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Template_JSON extends Template {

	/**
	 * Template_JSON works with .json files
	 *
	 * @var     string
	 * @access  protected
	 * @see     Template::$_extension
	 */
	protected $_extension = 'json';
	
	/**
	 * Template directory
	 *
	 * @access  protected
	 */
	protected $_dir = 'templates/json';

	/**
	 * The rendering method, wooo!
	 *
	 * @param   ViewModel  Data to be passed to template
	 * @return  string     Rendered template
	 */
	public function render(ViewModel $view)
	{
		$template = file_get_contents($this->filename());
		$json = json_decode($template);
		
		foreach ($json as $_property => $_value)
		{
			if (method_exists($view, $_property))
			{
				$json->{$_property} = $view->{$_property}();
			}
			else if (isset($view->{$_property}))
			{
				$json->{$_property} = $view->{$_property};
			}
		}
		
		return json_encode($json);
	}


}