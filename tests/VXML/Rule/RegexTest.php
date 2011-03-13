<?php
namespace VXML\Rule;

class RegexTest extends TestCase 
{
    public function testEqual()
    {
        $rule = new Regex('firstname', array('pattern' => '/\p{L}+/x'));
        $this->assertTrue($rule->execute($this->context, $this->response));
    }
    
    public function testNotEqual()
    {
        $rule = new Regex('firstname', array('pattern' => '/\[0-9]+/x'));
        $this->assertFalse($rule->execute($this->context, $this->response));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingPatternOptionError()
    {
        $rule = new Regex('firstname');
        $rule->execute($this->context, $this->response);
    }
}