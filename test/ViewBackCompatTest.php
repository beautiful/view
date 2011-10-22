<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * These tests are grabbed from the Kohana core tests.
 *
 * @package    Beautiful
 * @subpackage Beautiful View
 * @category   Tests
 *
 * @author     Kohana Team
 * @copyright  (c) 2008-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class ViewBackCompatTest extends PHPUnit_Framework_TestCase {

	/**
	 * Provider for test_instaniate.
	 *
	 * @return array
	 */
	public function provider_instantiate()
	{
		return array(
			array('kohana/error', FALSE),
			array('test.css',     FALSE),
			array('doesnt_exist', TRUE),
		);
	}

	/**
	 * Tests that we can instantiate a view file.
	 * 
	 * @test
	 * @dataProvider provider_instantiate
	 *
	 * @return null
	 */
	public function test_instantiate($path, $expects_exception)
	{
		try
		{
			$view = new View($path);
			$this->assertFalse($expects_exception);
		}
		catch(View_Exception $e)
		{
			$this->assertTrue($expects_exception);
		}
	}
}
