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
		if (isset($path))
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
			if ($this->_path === NULL)
			{
				$path = Kohana::find_file($this->_dir, $path, $this->_extension);
				
				if ($path === FALSE)
				{
					throw new Kohana_View_Exception(
						'The requested view :file could not be found',
						array(':file' => $file));
				}
				
				$this->_path = $path;
			}
			
			return $this->_path;
		}
		
		$this->_path = $path;
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