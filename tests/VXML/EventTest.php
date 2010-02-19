<?php
use VXML\Rule;

require_once 'PHPUnit\Framework\TestCase.php';

/**
 * Event test case.
 */
class VXML_EventTest extends PHPUnit_Framework_TestCase {
	
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
		
		$this->rule = new VXML\Rule\Equal('field_a', array('equal' => 'test'));
		$this->context = new VXML\Context(array('field_a' => 'test'));
		$this->response = new VXML\Response();
		
		$this->event = new VXML\Event($this->rule, $this->context, $this->response);
	}
	
	protected function tearDown() 
	{
		$this->event = null;
		parent::tearDown ();
	}
	
	public function testGetRule()
	{
		$this->assertType('VXML\Rule\RuleAbstract', $this->event->getRule());
	}
	
	public function testGetContext()
	{
		$this->assertType('VXML\Context', $this->event->getContext());
	}
	
	public function testGetResponse()
	{
		$this->assertType('VXML\Response', $this->event->getResponse());
	}
}