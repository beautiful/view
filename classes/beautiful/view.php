<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Beautiful View.
 *
 * @package     Beautiful
 * @subpackage  Beautiful View
 * @category    View
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class Beautiful_View {

	/**
	 * Factory method.
	 *
	 * @param   mixed  
	 * @param   mixed  
	 * @return  $this
	 */
	public static function factory($template = NULL, $viewmodel = NULL)
	{
		$class = get_called_class();
		return new $class($template, $viewmodel);
	}

	/**
	 * @var     Template
	 * @access  protected
	 */
	protected $_template;
	
	/**
	 * @var     ViewModel
	 * @access  protected
	 */
	protected $_viewmodel;

	/**
	 * Sets [Template] and [ViewModel]. If a string is passed as $template
	 * then it will be used a the path to the template and a Template
	 * instance will be created using [Template::$default_class].
	 *
	 * If an array is passed as $viewmodel then [ViewModel::$default_class]
	 * will be used to create a ViewModel instance and then the data will
	 * be set on that object.
	 *
	 * @param   mixed  
	 * @param   mixed  
	 * @return  void
	 */
	public function __construct($template = NULL, $viewmodel = NULL)
	{
		if ($template !== NULL)
		{
			if (is_string($template))
			{
				$this->set_filename($template);
			}
			else if ($template instanceof Template)
			{
				$this->template($template);
			}
			else
			{
				throw new View_Exception('Either a string path or Template instance should be passed into Beautiful_View::__construct()');
			}
		}
		
		if ($viewmodel !== NULL)
		{
			if (is_array($viewmodel))
			{
				$this->viewmodel()->set($viewmodel);
			}
			else if ($viewmodel instanceof ViewModel)
			{
				$this->viewmodel($viewmodel);
			}
			else
			{
				throw new View_Exception('Either an array or ViewModel instance should be passed into Beautiful_View::__construct()');
			}
		}
	}
	
	/**
	 * Set/Get Template. If getting and no template is set then
	 * we create an instance using [Template::$default_class].
	 *
	 * @param   Template  
	 * @return  $this
	 */
	public function template(Template $template = NULL)
	{
		if ($template === NULL)
		{
			if ($this->_template === NULL)
			{
				$class = Template::$default_class;
				$this->_template = new $class;
			}
		
			return $this->_template;
		}
		
		$this->_template = $template;
		return $this;
	}
	
	/**
	 * Get/Set [ViewModel]. If getting and no [ViewModel] set we then 
	 * create an instance using [ViewModel::$default_class].
	 *
	 * @param   ViewModel
	 * @return  ViewModel
	 */
	public function viewmodel(ViewModel $viewmodel = NULL)
	{
		if ($viewmodel === NULL)
		{
			if ($this->_viewmodel === NULL)
			{
				$class = ViewModel::$default_class;
				$this->_viewmodel = new $class;
			}
			
			return $this->_viewmodel;
		
		}
		
		$this->_viewmodel = $viewmodel;
		return $this;
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
		return $this->viewmodel()->{$key};
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
		$model = $this->viewmodel();
		return isset($model->{$key});
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
		$model = $this->viewmodel();
		unset($model->{$key});
	}
	
	/**
	 * Magic method, returns the output of [View::render()].
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
		$this->template()->path($file);
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
		$this->viewmodel()->bind($key, $value);
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
		$this->viewmodel()->bind($key, $value);
		return $this;
	}

	/**
	 * Passes [ViewModel] to [Template::render()] and returns
	 * a final string.
	 *
	 * @param    mixed   Can be Template or string path to template
	 * @return   string
	 */
	public function render($template = NULL)
	{
		if ($template instanceOf Template)
		{
			$this->template($template);
		}
		else if (is_string($template))
		{
			$this->set_filename($template);
		}
		
		return (string) $this->template()->render($this->viewmodel());
	}

}
