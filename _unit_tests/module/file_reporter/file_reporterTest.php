<?php
 use t0t1\mysfw\module;
 use t0t1\mysfw\frame;


//XXX To be completed
// require_once 'vfsStream/vfsStream.php';

 // XXX temp
 require_once '_unit_tests/unit_testing_init.php';
 $ut_initializer = new unit_testing_initializer();
 $ut_initializer->load('module/configurator/configurator.php');
 $ut_initializer->load('module/file_reporter/file_reporter.php');

 class file_reporterTest extends PHPUnit_Framework_TestCase {
  protected $_x;

  public function init_configurator($configurator) {
   $map = [
    ['report_dir', '_default_', 'relative dir/'],
    ['root', '_default_', '/root/'],
    ['report_file_name', '_default_', 'report.log']
    ];
   
   $configurator
    ->expects($this->any())
    ->method('inform')
    ->will($this->returnValueMap($map));

  }

  public function setUp() {
   $this->_x = new module\file_reporter;
   $mocked_popper = $this->getMock('t0t1\mysfw\frame\contract\popper');
   $mocked_configurator = $this->getMock('t0t1\mysfw\frame\contract\configurator');
   $mocked_file_utility = $this->getMock('t0t1\mysfw\frame\contract\file_utility', ['project_full_path','full_path','is_path_absolute']);
   $mocked_file_utility->expects($this->any())
	   ->method('project_full_path')
	   ->willReturn('/non-existent dir/file.prout');
   $mocked_popper->expects($this->any())
	   ->method('pop')
	   ->will($this->returnValueMap([['file_utility', '_default_', null, $mocked_file_utility]]));
   $this->init_configurator($mocked_configurator);
   $this->_x->set_popper($mocked_popper);
   $this->_x->set_configurator($mocked_configurator);
   //$this->_x->get_ready();
  }

  /**
   * @expectedException \t0t1\mysfw\frame\exception\dna
   * @expectedExceptionMessage Failed to open report file `/non-existent dir/file.prout`
   **/
  public function test_exception_file_not_found() {
   $this->_x->get_ready();
   $this->assertEquals($this->_x->get_file(), '/non-existent dir/file.prout');
  }

  public function test_consecutive_eid_are_different() {
   $this->assertNotEquals($this->_x->build_eid(), $this->_x->build_eid());
  }

}
