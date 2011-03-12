<?php
class VXML_ContextTest extends PHPUnit_Framework_TestCase 
{
	/**
	 * @var VXML\Context
	 */
	private $context;
	
	private $mockData = null;
	
	protected function setUp() 
	{
		parent::setUp ();
		
		$this->mockData = array(
			'level_one_a' => array(
				'level_two_a' => 'value_a',
				'level_two_b' => 'value_b'), 
			'level_one_b' => 'value_c',
			'level_one_c' => 'value_d'
		);
		
		$this->context = new VXML\Context($this->mockData);
	}
	
	protected function tearDown() 
	{
		$this->context = null;
		parent::tearDown ();
	}
	
	public function testConstants()
	{
		$this->assertTrue(defined('VXML\Context::SEPERATOR'));
		$this->assertTrue(defined('VXML\Context::WILDCARD'));
		$this->assertTrue(defined('VXML\Context::RELATIVE'));
		$this->assertTrue(defined('VXML\Context::PARENT'));
		$this->assertTrue(defined('VXML\Context::ALL_TARGETS'));
	}
	
	public function testSaveRestore() 
	{
		$this->context->setRelativeTarget('level_one_a');
		$this->context->save();
		$this->context->setRelativeTarget('level_two_a');
		$this->context->restore();
		$this->assertEquals(array('level_one_a'), $this->context->getResolvedTarget());
	}
	
	public function testSimpleRelativeTarget() 
	{
		$this->context->setRelativeTarget('level_one_a');
		$this->assertEquals(array('level_one_a'), $this->context->getResolvedTarget());
	}
	
	public function testMultipleLevelTarget()
	{
		$this->context->setRelativeTarget('level_one_a/level_two_a');
		$this->assertEquals(array('level_one_a/level_two_a'), $this->context->getResolvedTarget());
	}
	
	public function testAbsoluteTarget()
	{
		$this->context->setRelativeTarget('level_one_a/level_two_a');
		$this->context->setRelativeTarget('/level_one_b');
		$this->assertEquals(array('./level_one_b'), $this->context->getResolvedTarget());
	}
	
	public function testRelativeTarget()
	{
		$this->context->setRelativeTarget('level_one_a');
		$this->context->setRelativeTarget('level_two_a');
		$this->assertEquals(array('level_one_a/level_two_a'), $this->context->getResolvedTarget());
	}
	
	public function testGetPassedValue() 
	{
		$this->context->setRelativeTarget('level_one_a');
		$this->assertEquals($this->mockData['level_one_a'], $this->context->getPassedValue());
	}
	
	public function testGetPassedValues() 
	{
		$this->context->setRelativeTarget('level_one_a');
		$this->assertEquals(array('level_one_a' => $this->mockData['level_one_a']), $this->context->getPassedValues(VXML\Context::ALL_TARGETS));
	}
	
	public function testGetPassedValuesCustomProto()
	{
		$proto = array('field_a', 'field_b');
		$this->context->setRelativeTarget(array('level_one_b', 'level_one_c'));
		$this->assertEquals(array('field_a' => 'value_c', 'field_b' => 'value_d'), $this->context->getPassedValues($proto));
	}
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testgetPassedValuesCustomProtoTargetCountError()
	{
		$proto = array('field_a', 'field_b');
		$this->context->setRelativeTarget('level_one_b');
		$this->context->getPassedValues($proto);
	}
}