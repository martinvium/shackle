<?php
namespace VXML\Rule;

class StringLengthTest extends TestCase 
{
	public function testValidLength()
	{
		$rule = new StringLength('firstname', array('min' => 6, 'max' => 10));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testStringTooShort()
	{
		$rule = new StringLength('firstname', array('min' => 7, 'max' => 20));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	public function testStringTooLong()
	{
		$rule = new StringLength('firstname', array('min' => 4, 'max' => 5));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	public function testOnlyMinimumOptionValid()
	{
		$rule = new StringLength('firstname', array('min' => 6));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testOnlyMinimumOptionTooShort()
	{
		$rule = new StringLength('firstname', array('min' => 7));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	public function testOnlyMaximumOptionValid()
	{
		$rule = new StringLength('firstname', array('max' => 6));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	public function testOnlyMaximumOptionTooLong()
	{
		$rule = new StringLength('firstname', array('max' => 5));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	public function testCharsetOption()
	{
		$rule = new StringLength('firstname', array('max' => 6, 'charset' => 'iso-8859-1'));
		$this->assertFalse($rule->execute($this->context, $this->response));
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testMissingBothMinAndMaxOptions()
	{
		$rule = new StringLength('firstname', array());
		$rule->execute($this->context, $this->response);
	}
}