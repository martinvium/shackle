<?php
use VXML\Rule;

require_once 'PHPUnit\Framework\TestCase.php';

/**
 * Response test case.
 */
class VXML_ResponseTest extends PHPUnit_Framework_TestCase 
{
	/**
	 * @var VXML\Response
	 */
	private $response;
	
	/**
	 * @var VXML\Rule\Equal
	 */
	private $mockRule;
	
	protected function setUp() 
	{
		parent::setUp ();
		
		$this->response = new VXML\Response();
		$this->mockRule = new VXML\Rule\Equal('mock', array('message' => 'message'));
	}
	
	protected function tearDown() 
	{
		$this->response = null;
		$this->equalsRule = null;
		parent::tearDown ();
	}
	
	public function testConstants()
	{
		$this->assertTrue(defined('VXML\Response::MSG_FAILURE'));
		$this->assertTrue(defined('VXML\Response::MSG_SUCCESS'));
		$this->assertTrue(defined('VXML\Response::MSG_DEBUG'));
	}
	
	public function testMergeResponse()
	{
		$this->response->addSuccess($this->mockRule);
		$this->response->addFailure($this->mockRule);
		$this->response->addDebug($this->mockRule, 'debug');
		
		$mergedResponse = new VXML\Response();
		$mergedResponse->merge($this->response);
		
		$this->assertEquals($this->response->getAllMessages(), $mergedResponse->getAllMessages());
	}
	
	public function testConvertFailuresToDebug()
	{
		$this->response->addFailure($this->mockRule);
		$this->response->convertFailuresToDebug();
		$this->assertEquals(0, count($this->response->getFailureMessages()));
		$this->assertEquals(1, count($this->response->getDebugMessages()));
	}
	
	public function testAddFailure()
	{
		$this->response->addFailure($this->mockRule);
		$this->assertEquals(1, count($this->response->getFailureMessages()));
	}
	
	public function testAddSuccess()
	{
		$this->response->addSuccess($this->mockRule);
		$this->assertEquals(1, count($this->response->getSuccessMessages()));
	}
	
	public function testAddDebug()
	{
		$this->response->addDebug($this->mockRule, 'debug');
		$this->assertEquals(1, count($this->response->getDebugMessages()));
	}
	
	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testAddDebugArgDebugRequired()
	{
		$this->response->addDebug($this->mockRule);
		$this->assertEquals(1, count($this->response->getDebugMessages()));
	}
	
	public function testGetAllMessages()
	{
		$this->response->addSuccess($this->mockRule);
		$this->response->addFailure($this->mockRule);
		$this->response->addDebug($this->mockRule, 'debug');
		$this->assertEquals(3, count($this->response->getAllMessages()));
	}
	
	public function testRemoveByRule()
	{
		$mockRuleB = new VXML\Rule\Equal('mock2');
		$this->response->addSuccess($this->mockRule);
		$this->response->addSuccess($mockRuleB);
		$this->response->removeByRule($this->mockRule);
		
		$messages = $this->response->getSuccessMessages();
		$this->assertEquals(1, count($messages));
		$remainingMessage = current($messages);
		$this->assertEquals($mockRuleB, $remainingMessage['rule']);
	}
	
	public function testSuccessMessageFormat()
	{
		$this->response->addSuccess($this->mockRule, 'debug');
		$message = current($this->response->getSuccessMessages());
		$this->assertEquals(VXML\Response::MSG_SUCCESS, $message['type']);
		$this->assertEquals($this->mockRule, $message['rule']);
		$this->assertEquals(array(), $message['target'], 'target should be empty, because rule has not been executed');
		$this->assertEquals(null, $message['message']);
		$this->assertEquals('debug', $message['debug']);
	}
	
	public function testFailureMessageFormat()
	{
		$this->response->addFailure($this->mockRule, 'debug');
		$message = current($this->response->getFailureMessages());
		$this->assertEquals(VXML\Response::MSG_FAILURE, $message['type']);
		$this->assertEquals($this->mockRule, $message['rule']);
		$this->assertEquals(array(), $message['target'], 'target should be empty, because rule has not been executed');
		$this->assertEquals('message', $message['message']);
		$this->assertEquals('debug', $message['debug']);
	}
	
	public function testDebugMessageFormat()
	{
		$this->response->addDebug($this->mockRule, 'debug');
		$message = current($this->response->getDebugMessages());
		$this->assertEquals(VXML\Response::MSG_DEBUG, $message['type']);
		$this->assertEquals($this->mockRule, $message['rule']);
		$this->assertEquals(array(), $message['target'], 'target should be empty, because rule has not been executed');
		$this->assertEquals(null, $message['message']);
		$this->assertEquals('debug', $message['debug']);
	}
}