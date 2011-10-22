<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests the View class.
 *
 * @package     Beautiful
 * @subpackage  Beautiful View
 * @category    Tests
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class ViewTest extends PHPUnit_Framework_TestCase {

	/**
	 * Data provider for testing bad params.
	 */
	public function dataProviderBadParams()
	{
		return array(
			array(FALSE),
			array(TRUE),
			array(new StdClass),
		);
	}

	/**
	 * Template can only be Template or string.
	 * 
     * @dataProvider       dataProviderBadParams
     * @expectedException  View_Exception
     */
	public function testExceptionThrownOnInvalidTemplateParam($param)
	{
		new View($param);
	}

	/**
	 * Template can only be Template or string.
	 * 
     * @dataProvider       dataProviderBadParams
     * @expectedException  View_Exception
     */
	public function testExceptionThrownOnInvalidViewModelParam($param)
	{
		new View($param);
	}

	/**
	 * Template should default to Template_PHP.
     */
	public function testDefaultTemplateClass()
	{
		$view = new View;
		$this->assertInstanceOf('Template_PHP', $view->template());
	}
	
	/**
	 * Test overriding of ViewModel::$default_class.
	 */
	public function testCustomDefaultTemplateClass()
	{
		Template::$default_class = 'Template_Mustache';
		$view = new View;
		$this->assertInstanceOf('Template_Mustache', $view->template());
		
		// Reset
		Template::$default_class = 'Template_PHP';
	}

	/**
	 * ViewModel should default to ViewModel.
     */
	public function testDefaultViewModelClass()
	{
		$view = new View;
		$this->assertInstanceOf('ViewModel', $view->viewmodel());
	}
	
	/**
	 * Test overriding of ViewModel::$default_class.
	 */
	public function testCustomDefaultViewModelClass()
	{
		ViewModel::$default_class = 'ViewModel_Test';
		$view = new View;
		$this->assertInstanceOf('ViewModel_Test', $view->viewmodel());
		
		// Reset
		ViewModel::$default_class = 'ViewModel';
	}

	/**
	 * ViewModel should default to ViewModel even if array passed.
     */
	public function testDefaultViewModelClassWithArray()
	{
		$view = new View(NULL, array(
			'a' => 'value',
			'b' => 'another',
		));
		$this->assertInstanceOf('ViewModel', $view->viewmodel());
		$this->assertSame('value', $view->viewmodel()->a);
		$this->assertSame('another', $view->viewmodel()->b);
	}

	/**
	 * Test View::set().
	 */
	public function testViewSet()
	{
		$viewmodel = new ViewModel;
		$view = new View(NULL, $viewmodel);
		$view->set('testing_property', 'A value');
		$this->assertSame('A value', $viewmodel->testing_property);
	}

	/**
	 * Test View::bind().
	 */
	public function testViewBind()
	{
		$viewmodel = new ViewModel;
		$view = new View(NULL, $viewmodel);
		$bindable = 'A value';
		$view->bind('testing_bound', $bindable);
		$this->assertSame('A value', $viewmodel->testing_bound);
		$bindable = 'A changed value';
		$this->assertSame('A changed value', $viewmodel->testing_bound);
	}

	/**
	 * Test View::__get().
	 */
	public function testViewMagicGet()
	{
		$viewmodel = new ViewModel;
		$viewmodel->testing_property = 'Bob';
		$view = new View(NULL, $viewmodel);
		$this->assertSame('Bob', $view->testing_property);
	}

	/**
	 * Test View::__isset().
	 */
	public function testViewMagicIsset()
	{
		$viewmodel = new ViewModel;
		$viewmodel->testing_property = 'Bob';
		$view = new View(NULL, $viewmodel);
		$this->assertTrue(isset($view->testing_property));
	}

	/**
	 * Test View::__set().
	 */
	public function testViewMagicSet()
	{
		$viewmodel = new ViewModel;
		$view = new View(NULL, $viewmodel);
		$view->testing_property = 'A value';
		$this->assertSame('A value', $viewmodel->testing_property);
	}

	/**
	 * Test View::__unset().
	 */
	public function testViewMagicUnset()
	{
		$viewmodel = new ViewModel;
		$viewmodel->testing_property = 'A value';
		$view = new View(NULL, $viewmodel);
		unset($view->testing_property);
		$this->assertFalse(isset($view->testing_property));
	}

	/**
	 * Test View::set_filename().
	 */
	public function testViewSetFilename()
	{
		$template = new Template_PHP('example');
		$view = new View(new Template_Mock('example'));
		$view->set_filename('example');
		$this->assertSame(__DIR__.'/views/example.php', $template->path());
	}
	
	/**
	 * Test View::render().
	 */
	public function testViewRender()
	{
		$view = new View(new Template_Mock('example'));
		$this->assertSame('An example render', $view->render());
	}
	
	/**
	 * Test View::__toString().
	 */
	public function testViewToString()
	{
		$view = new View(new Template_Mock('example'));
		$this->assertSame('An example render', (string) $view);
	}
	
	/**
	 * Test Template overridden when a Template object is
	 * passed to View::render().
	 */
	public function testTemplateOverrideViaViewRender()
	{
		$view = new View(new Template_PHP('example'));
		$this->assertSame('An example render', $view->render(new Template_Mock('example')));
	}
	
}
