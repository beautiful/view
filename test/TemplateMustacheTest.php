<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Test Template_Mustache.
 *
 * @package     Beautiful
 * @subpackage  Beautiful View
 * @category    Tests
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class TemplateMustacheTest extends PHPUnit_Framework_TestCase {

	/**
	 * Test Template_Mustache::path().
	 */
	public function testPath()
	{
		$template = new Template_Mustache('test');
		$this->assertSame(__DIR__.'/templates/test.mustache', $template->path());
	}
	
	/**
	 * Provides TemplateMustacheTest::testRender() with ViewModels.
	 */
	public function dataProviderViewModel()
	{
		$data = array(
			array(new ViewModel, array('a' => 1, 'b' => 'string', 'c' => null), "1string\n"),
			array(new ViewModel, array('a' => NULL, 'b' => 'string', 'c' => "\""), "string&quot;\n"),
			array(new ViewModel, array(), "\n"),
			array(new ViewModel_Test, array('a' => NULL, 'b' => 'string', 'c' => "\""), "string&quot;\ntest"),
			array(new ViewModel_Test, array('a' => 'nice'), "nice\ntest"),
			array(new ViewModel_Test, array('a' => 'nice', 'test' => 'alt'), "nice\ntest"),
		);
		$final = array();
		foreach ($data as $_d)
		{
			$_d[0]->set($_d[1]);
			$final[] = array('test', $_d[0], $_d[2]);
		}
		return $final;
	}

	/**
	 * Test Template_Mustache::render() with different Templates
	 * and ViewModels.
	 * 
	 * @dataProvider  dataProviderViewModel
	 */
	public function testRender($template_path, $viewmodel, $expected)
	{
		$template = new Template_Mustache($template_path);
		$actual = $template->render($viewmodel);
		$this->assertSame($expected, $actual);
	}

}
