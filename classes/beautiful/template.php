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
	 * Template extension
	 *
	 * @protected
	 */
	protected $_extension = NULL;
	
	/**
	 * Template directory
	 *
	 * @access  protected
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
	public function __construct($filename = NULL)
	{
		if (isset($filename))
		{
			$this->set_filename($filename);
		}
	}

	/**
	 * Set template location.
	 *
	 * @param   string
	 * @return  $this
	 */
	public function set_filename($file)
	{
		if (($path = Kohana::find_file($this->_dir, $file, $this->_extension)) === FALSE)
		{
			throw new Kohana_View_Exception('The requested view :file could not be found', array(
				':file' => $file,
			));
		}

		// Store the file path locally
		$this->_path = $path;

		return $this;
	}
	
	/**
	 * Get template location.
	 *
	 * @return  string
	 */
	public function get_filename(ViewModel $view = NULL)
	{
		if (isset($view) && $this->_path === NULL)
		{
			$this->set_filename(
				$this->_detect_filename_from_view($view)
			);
		}
		
		return $this->_path;
	}
	
	/**
	 * Detect the template name from the class name.
	 *
	 * @param   ViewModel
	 * @return  string
	 */
	protected function _detect_filename_from_view(ViewModel $view)
	{
		// Start creating the template path from the class name
		$template = explode('_', get_class($view));

		// Remove "View" prefix
		array_shift($template);

		// Convert name parts into a path
		$template = strtolower(implode('/', $template));

		return $template;
	}

	/**
	 * Render data into template
	 *
	 * @param  mixed  data
	 * @abstract
	 */
	abstract public function render(ViewModel $view);

}