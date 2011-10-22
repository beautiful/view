<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Test Template_JSON.
 *
 * @package     Beautiful
 * @subpackage  Beautiful View
 * @category    Tests
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
class TemplateJSONTest extends PHPUnit_Framework_TestCase {

	/**
	 * Test Template_JSON::path().
	 */
	public function testPath()
	{
		$template = new Template_JSON('test');
		$this->assertSame(__DIR__.'/templates/json/test.json', $template->path());
	}
	
	/**
	 * Provides TemplateJSONTest::testRender() with ViewModels.
	 */
	public function dataProviderViewModel()
	{
		$data = array(
			array(new ViewModel, array('a' => 1, 'b' => 'string', 'c' => null), '{"a":1,"b":"string","c":"c","test":"default"}'),
			array(new ViewModel, array('a' => NULL, 'b' => 'string', 'c' => "\""), '{"a":"a","b":"string","c":"\"","test":"default"}'),
			array(new ViewModel, array(), '{"a":"a","b":"b","c":"c","test":"default"}'),
			array(new ViewModel_Test, array('a' => NULL, 'b' => 'string', 'c' => "\""), '{"a":"a","b":"string","c":"\"","test":"test"}'),
			array(new ViewModel_Test, array('a' => 'nice'), '{"a":"nice","b":"b","c":"c","test":"test"}'),
			array(new ViewModel_Test, array('a' => 'nice', 'test' => 'alt'), '{"a":"nice","b":"b","c":"c","test":"test"}'),
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
	 * Test Template_JSON::render() with various Templates
	 * and ViewModels.
	 * 
	 * @dataProvider  dataProviderViewModel
	 */
	public function testRender($template_path, $viewmodel, $expected)
	{
		$template = new Template_JSON($template_path);
		$actual = $template->render($viewmodel);
		$this->assertSame($expected, $actual);
	}

}