<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Beautiful Default Template
 *
 * @package     Beautiful
 * @subpackage  Beautiful View
 * @category    Template
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Template_PHP extends Template {
	
	/**
	 * Template directory set to views for historic reasons. I prefer to use
	 * a directory called "templates" as in [Template_Mustache].
	 */
	public static $dir = 'views';

	/**
	 * Default driver works with .php files.
	 */
	public static $ext = 'php';

	/**
	 * Captures the output that is generated when a view is included.
	 * The view data will be extracted to make local variables. This method
	 * is static to prevent object scope resolution.
	 *
	 *     $output = Template_Default::capture($file, $data);
	 *
	 * @param   string     filename
	 * @param   ViewModel
	 * @return  string
	 */
	protected static function capture($kohana_view_filename, ViewModel $kohana_view_data)
	{
		if (get_class($kohana_view_data) === 'ViewModel')
		{
			// This class is plain view model, extract
			// as an array
			$kohana_view_data = (array) $kohana_view_data;
			extract($kohana_view_data, EXTR_SKIP);
		}
		else
		{
			// We only want the $view variable
			// in scope of the template
			$view = $kohana_view_data;
			unset($kohana_view_data);
		}

		// Capture the view output
		ob_start();

		try
		{
			// Load the view within the current scope
			include $kohana_view_filename;
		}
		catch (Exception $e)
		{
			// Delete the output buffer
			ob_end_clean();

			// Re-throw the exception
			throw $e;
		}

		// Get the captured output and close the buffer
		return ob_get_clean();
	}

	/**
	 * The rendering method, wooo!
	 *
	 * @param   ViewModel  Data to be passed to template
	 * @return  string     Rendered template
	 * @uses    Template_Default::capture
	 * @uses    Template::set_filename
	 */
	public function render(ViewModel $view)
	{
		return self::capture($this->path(), $view);
	}

}