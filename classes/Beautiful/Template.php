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
	 * Template directory.
	 */
	public static $dir = 'templates';

	/**
	 * Template extension.
	 */
	public static $ext = NULL;

	/**
	 * Template file path.
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
	public function __construct($path = NULL, $partials = NULL)
	{
		if ($path !== NULL)
		{
			$this->path($path);
		}

		if (is_array($partials) AND method_exists($this, 'partial'))
		{
			foreach($partials as $name => $path)
			{
				$this->partial($name, $path);
			}
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

		$final_path = Kohana::find_file(static::$dir, $path, static::$ext);

		if ($final_path === FALSE)
		{
			throw new View_Exception(
				'The requested view :path could not be found',
				array(':path' => static::$dir.'/'.$path.'.'.static::$ext));
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
