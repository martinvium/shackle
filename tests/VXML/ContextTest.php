<?php
namespace VXML;

class ContextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Context
     */
    private $_context;
    
    private $_mockData = null;
    
    protected function setUp() 
    {
        parent::setUp ();
        
        $this->_mockData = array(
            'level_one_a' => array(
                'level_two_a' => 'value_a',
                'level_two_b' => 'value_b'), 
            'level_one_b' => 'value_c',
            'level_one_c' => 'value_d'
        );
        
        $this->_context = new Context($this->_mockData);
    }
    
    protected function tearDown() 
    {
        $this->_context = null;
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
        $this->_context->setRelativeTarget('level_one_a');
        $this->_context->save();
        $this->_context->setRelativeTarget('level_two_a');
        $this->_context->restore();
        $this->assertEquals(array('level_one_a'), $this->_context->getResolvedTarget());
    }
    
    public function testSimpleRelativeTarget() 
    {
        $this->_context->setRelativeTarget('level_one_a');
        $this->assertEquals(array('level_one_a'), $this->_context->getResolvedTarget());
    }
    
    public function testMultipleLevelTarget()
    {
        $this->_context->setRelativeTarget('level_one_a/level_two_a');
        $this->assertEquals(array('level_one_a/level_two_a'), $this->_context->getResolvedTarget());
    }
    
    public function testAbsoluteTarget()
    {
        $this->_context->setRelativeTarget('level_one_a/level_two_a');
        $this->_context->setRelativeTarget('/level_one_b');
        $this->assertEquals(array('./level_one_b'), $this->_context->getResolvedTarget());
    }
    
    public function testRelativeTarget()
    {
        $this->_context->setRelativeTarget('level_one_a');
        $this->_context->setRelativeTarget('level_two_a');
        $this->assertEquals(array('level_one_a/level_two_a'), $this->_context->getResolvedTarget());
    }
    
    public function testGetPassedValue() 
    {
        $this->_context->setRelativeTarget('level_one_a');
        $this->assertEquals($this->_mockData['level_one_a'], $this->_context->getPassedValue());
    }
    
    public function testGetPassedValues() 
    {
        $this->_context->setRelativeTarget('level_one_a');
        $this->assertEquals(array('level_one_a' => $this->_mockData['level_one_a']), $this->_context->getPassedValues(Context::ALL_TARGETS));
    }
    
    public function testGetPassedValuesCustomProto()
    {
        $proto = array('field_a', 'field_b');
        $this->_context->setRelativeTarget(array('level_one_b', 'level_one_c'));
        $this->assertEquals(array('field_a' => 'value_c', 'field_b' => 'value_d'), $this->_context->getPassedValues($proto));
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testgetPassedValuesCustomProtoTargetCountError()
    {
        $proto = array('field_a', 'field_b');
        $this->_context->setRelativeTarget('level_one_b');
        $this->_context->getPassedValues($proto);
    }
}