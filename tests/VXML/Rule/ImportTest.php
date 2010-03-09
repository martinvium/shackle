<?php
use VXML\Rule;

require_once 'TestCase.php';

class VXML_Rule_ImportTest extends VXML_Rule_TestCase 
{
	public function testImportPHPFile()
	{
		$rule = new Rule\Import('.', array('path' => 'res/import.php'));
		$this->assertTrue($rule->execute($this->context, $this->response));
	}
	
	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testMissingPathOption()
	{
		$rule = new Rule\Import('.', array());
		$rule->execute($this->context, $this->response);
	}
	
	public function testCompositeFeatures()
	{
		$this->markTestIncomplete('Test add, min, max etc');
	}
}