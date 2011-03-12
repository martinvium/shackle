<?php
use VXML\Rule;

class VXML_Rule_ImportTest extends VXML_Rule_TestCase 
{
	public function testImportPHPFile()
	{
        $path = dirname(__DIR__) . '/res/import.php';
		$rule = new Rule\Import('.', array('path' => $path));
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