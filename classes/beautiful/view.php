<?php defined('SYSPATH') or die('No direct script access.');

class Beautiful_View {

	/**
	 * @param   mixed  
	 * @param   mixed  
	 * @return  $this
	 */
	public static function factory($template = NULL, $view_model = NULL)
	{
		$class = get_called_class();
		return new $class($template, $view_model);
	}

	/**
	 * @var     Beautiful_Template
	 * @access  protected
	 */
	protected $_template;
	
	/**
	 * @var     Beautiful_ViewModel
	 * @access  protected
	 */
	protected $_model;

	/**
	 * @param   mixed  
	 * @param   mixed  
	 * @return  void
	 */
	public function __construct($template = NULL, $view_model = NULL)
	{
		if (is_string($template))
		{
			$this->set_filename($template);
		}
		else if ($template instanceof Template)
		{
			$this->set_template($template);
		}
		else if ($template instanceof ViewModel)
		{
			// ViewModel was passed in as first param
			// this is allowed
			$this->set_model($template);
		}
		
		if (isset($view_model))
		{
			$this->set_model($view_model);
		}
	}
	
	/**
	 * @param   mixed  
	 * @return  $this
	 */
	public function set_template($template)
	{
		$this->_template = $template;
		return $this;
	}
	
	/**
	 * @return  Beautiful_Template
	 */
	public function get_template()
	{
		if ($this->_template instanceof Template)
		{
			// Already prepared
		}
		else if ($this->_template === NULL)
		{
			// Use default Template
			$this->_template = new Template_Default;
		}
		else
		{
			throw new Kohana_Exception('Template passed was'.
				' not an instance of Template.');
		}
		
		return $this->_template;
	}
	
	/**
	 * @param   mixed  
	 * @return  $this
	 */
	public function set_model($view_model)
	{
		if (is_array($view_model))
		{
			$this->_model = new ViewModel;
			$this->_model->set($view_model);
		}
		else
		{
			$this->_model = $view_model;
		}
		
		return $this;
	}
	
	/**
	 * @return  Beautiful_ViewModel
	 */
	public function get_model()
	{
		if ($this->_model instanceof ViewModel)
		{
			// Already prepared
		}
		else if ($this->_model === NULL)
		{
			$this->_model = new ViewModel;
		}
		else
		{
			throw new Kohana_Exception('ViewModel passed was'.
				' not an instance of ViewModel');
		}
		
		return $this->_model;
	}
	
	/**
	 * Magic method, searches for the given variable and returns its value.
	 * Local variables will be returned before global variables.
	 *
	 *     $value = $view->foo;
	 *
	 * [!!] If the variable has not yet been set, an exception will be thrown.
	 *
	 * @param   string  variable name
	 * @return  mixed
	 * @throws  Kohana_Exception
	 */
	public function & __get($key)
	{
		return $this->get_model()
			->get($key)
			;
	}

	/**
	 * Magic method, calls [View::set] with the same parameters.
	 *
	 *     $view->foo = 'something';
	 *
	 * @param   string  variable name
	 * @param   mixed   value
	 * @return  void
	 */
	public function __set($key, $value)
	{
		$this->set($key, $value);
	}

	/**
	 * Magic method, determines if a variable is set.
	 *
	 *     isset($view->foo);
	 *
	 * [!!] `NULL` variables are not considered to be set by [isset](http://php.net/isset).
	 *
	 * @param   string  variable name
	 * @return  boolean
	 */
	public function __isset($key)
	{
		$model = $this->get_model();
		return isset($model[$key]);
	}

	/**
	 * Magic method, unsets a given variable.
	 *
	 *     unset($view->foo);
	 *
	 * @param   string  variable name
	 * @return  void
	 */
	public function __unset($key)
	{
		$model = $this->get_model();
		unset($model->{$key});
	}
	
	/**
	 * Magic method, returns the output of [View::render].
	 *
	 * @return  string
	 * @uses    View::render
	 */
	public function __toString()
	{
		try
		{
			return $this->render();
		}
		catch (Exception $e)
		{
			// Display the exception message
			Kohana_Exception::handler($e);

			return '';
		}
	}
	
	/**
	 * Sets the view filename.
	 *
	 *     $view->set_filename($file);
	 *
	 * @param   string  view filename
	 * @return  View
	 */
	public function set_filename($file)
	{
		$this->get_template()
			->set_filename($file)
			;
		return $this;
	}

	/**
	 * Assigns a variable by name. Assigned values will be available as a
	 * variable within the view file:
	 *
	 *     // This value can be accessed as $foo within the view
	 *     $view->set('foo', 'my value');
	 *
	 * You can also use an array to set several values at once:
	 *
	 *     // Create the values $food and $beverage in the view
	 *     $view->set(array('food' => 'bread', 'beverage' => 'water'));
	 *
	 * @param   string   variable name or an array of variables
	 * @param   mixed    value
	 * @return  $this
	 */
	public function set($key, $value = NULL)
	{
		$this->get_model()
			->set($key, $value)
			;
		return $this;
	}

	/**
	 * Assigns a value by reference. The benefit of binding is that values can
	 * be altered without re-setting them. It is also possible to bind variables
	 * before they have values. Assigned values will be available as a
	 * variable within the view file:
	 *
	 *     // This reference can be accessed as $ref within the view
	 *     $view->bind('ref', $bar);
	 *
	 * @param   string   variable name
	 * @param   mixed    referenced variable
	 * @return  $this
	 */
	public function bind($key, & $value)
	{
		$this->get_model()
			->bind($key, $value)
			;
		return $this;
	}

	/**
	 * Renders the view object to a string. Global and local data are merged
	 * and extracted to create local variables within the view file.
	 *
	 *     $output = $view->render();
	 *
	 * @param    string  view filename
	 * @return   string
	 */
	public function render($template = NULL, $view_model = NULL)
	{
		if ($view_model)
		{
			$this->set_model($view_model);
			
			if ($template === NULL)
			{
				$this->set_filename();
			}
		}
		else if ($template)
		{
			$this->set_filename($template);
		}
		
		return $this->get_template()
			->render($this->get_model());
	}

}