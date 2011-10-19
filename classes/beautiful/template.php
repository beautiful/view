<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Beautiful Template
 *
 * @package     Beautiful
 * @subpackage  Beautiful View
 * @category    Template
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
abstract class Beautiful_Template {
	
	/**
	 * Default Template class.
	 */
	public static $default_class = 'Template_PHP';

	/**
	 * Template extension
	 *
	 * @protected
	 */
	protected $_extension = NULL;
	
	/**
	 * Template directory
	 *
	 * @protected
	 */
	protected $_dir = 'templates';

	/**
	 * Template file path
	 *
	 * @protected
	 */
	protected $_path = NULL;
	
	/**
	 * Create new instance.
	 *
	 * @param   string  Filename
	 * @return  void
	 */
	public function __construct($path = NULL)
	{
		if ($path !== $path)
		{
			$this->path($path);
		}
	}
	
	/**
	 * Get/Set path to template file.
	 *
	 * @return  string
	 */
	public function path($path = NULL)
	{
		if ($path === NULL)
		{
			return $this->_path;
		}

		$final_path = Kohana::find_file($this->_dir, $path, $this->_extension);
		
		if ($final_path === FALSE)
		{
			throw new Kohana_View_Exception(
				'The requested view :path could not be found',
				array(':path' => "{$this->_dir}/{$path}.{$this->_extension}"));
		}				
		
		$this->_path = $final_path;
		return $this;
	}

	/**
	 * Render data into template
	 *
	 * @param  mixed  data
	 * @abstract
	 */
	abstract public function render(ViewModel $view);

}