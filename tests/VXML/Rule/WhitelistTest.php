<?php
use VXML\Rule;

class VXML_Rule_WhitelistTest extends VXML_Rule_TestCase 
{
	public function testInWhitelist()
	{
		$whitelist = array('Jørgen', 'Hans', 'Inge');
		$rule = new Rule\Whitelist('firstname', array('options' => $whitelist));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testNotInWhitelist()
	{
		$whitelist = array('Hans', 'Inge');
		$rule = new Rule\Whitelist('firstname', array('options' => $whitelist));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	public function testInWhitelistStrict()
	{
		$whitelist = array('Jørgen', 'Hans', 'Inge');
		$rule = new Rule\Whitelist('firstname', array('options' => $whitelist, 'strict' => true));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testNotInWhitelistStrict()
	{
		$whitelist = array('jørgen', 'Hans', 'Inge');
		$rule = new Rule\Whitelist('firstname', array('options' => $whitelist, 'strict' => true));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testMissingOptionsOptionError()
	{
		$rule = new Rule\Whitelist('firstname');
		$rule->execute($this->context, $this->response);
	}
	
	public function testStrictOptionIsOptional()
	{
		try {
			$rule = new Rule\Whitelist('firstname', array('options' => array()));
			$rule->execute($this->context, $this->response);
		} catch (\InvalidArgumentException $e) {
			$this->fail();
		}
	}
}