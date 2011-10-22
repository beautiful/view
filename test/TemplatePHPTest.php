<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Test Template_PHP.
 *
 * @package     Beautiful
 * @subpackage  Beautiful View
 * @category    Tests
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class TemplatePHPTest extends PHPUnit_Framework_TestCase {

	/**
	 * Test Template_PHP::path().
	 */
	public function testPath()
	{
		$template = new Template_PHP('test');
		$this->assertSame(__DIR__.'/views/test.php', $template->path());
	}
	
	/**
	 * Provides TemplatePHPTest::testRender() with ViewModels.
	 */
	public function dataProviderViewModel()
	{
		$data = array(
			array(new ViewModel, array('a' => 1, 'b' => 'string', 'c' => null), '1string'),
			array(new ViewModel, array('a' => NULL, 'b' => 'string', 'c' => "\""), 'string"'),
			array(new ViewModel, array(), ''),
			array(new ViewModel_Test, array('a' => NULL, 'b' => 'string', 'c' => "\""), 'test'),
			array(new ViewModel_Test, array('a' => 'nice'), "nicetest"),
			array(new ViewModel_Test, array('a' => 'nice', 'test' => 'alt'), "nicetest"),
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
	 * Test Template_PHP::render() with different Templates
	 * and ViewModels.
	 * 
	 * @dataProvider  dataProviderViewModel
	 */
	public function testRender($template_path, $viewmodel, $expected)
	{
		$template = new Template_PHP($template_path);
		$actual = $template->render($viewmodel);
		$this->assertSame($expected, $actual);
	}

}
