<?php
namespace VXML;

class EventTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * @var VXML\Event
     */
    private $event;
    
    /**
     * @var VXML\Rule\RuleAbstract
     */
    private $rule;
    
    /**
     * @var VXML\Context
     */
    private $context;
    
    /**
     * @var VXML\Response
     */
    private $response;
    
    protected function setUp() 
    {
        parent::setUp ();
        
        $this->rule = new Rule\Equal('field_a', array('equal' => 'test'));
        $this->context = new Context(array('field_a' => 'test'));
        $this->response = new Response();
        
        $this->event = new Event($this->rule, $this->context, $this->response);
    }
    
    protected function tearDown() 
    {
        $this->event = null;
        parent::tearDown ();
    }
    
    public function testGetRule()
    {
        $this->assertInstanceOf('VXML\Rule\RuleAbstract', $this->event->getRule());
    }
    
    public function testGetContext()
    {
        $this->assertInstanceOf('VXML\Context', $this->event->getContext());
    }
    
    public function testGetResponse()
    {
        $this->assertInstanceOf('VXML\Response', $this->event->getResponse());
    }
}