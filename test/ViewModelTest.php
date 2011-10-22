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
class ViewModelTest extends PHPUnit_Framework_TestCase {
	
	public function testViewModelSet()
	{
		$viewmodel = new ViewModel;
		$viewmodel->set('a', 'value');
		$this->assertSame('value', $viewmodel->a);
	}
	
	public function testViewModelBind()
	{
		$viewmodel = new ViewModel;
		$a = 'value';
		$viewmodel->bind('a', $a);
		$this->assertSame('value', $viewmodel->a);
		$a = 'another';
		$this->assertSame('another', $viewmodel->a);
	}

}
