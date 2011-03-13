<?php
namespace VXML\Rule;

class EqualTest extends TestCase 
{
    public function testEqual()
    {
        $rule = new Equal('firstname', array('equal' => 'Jørgen'));
        $this->assertTrue($rule->execute($this->context, $this->response));
    }
    
    public function testNotEqual()
    {
        $rule = new Equal('firstname', array('equal' => 'Jørgenx'));
        $this->assertFalse($rule->execute($this->context, $this->response));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingEqualOptionError()
    {
        $rule = new Equal('firstname');
        $rule->execute($this->context, $this->response);
    }
}