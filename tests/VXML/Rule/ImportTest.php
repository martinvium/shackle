<?php
namespace VXML\Rule;

class ImportTest extends TestCase 
{
    public function testImportPHPFile()
    {
        $rule = new Import('.', array('path' => __DIR__ . '/_files/import.php'));
        $this->assertTrue($rule->execute($this->context, $this->response));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingPathOption()
    {
        $rule = new Import('.', array());
        $rule->execute($this->context, $this->response);
    }
    
    public function testCompositeFeatures()
    {
        $this->markTestIncomplete('Test add, min, max etc');
    }
}