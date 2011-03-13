<?php
namespace VXML\Rule;

class WhitelistTest extends TestCase 
{
    public function testInWhitelist()
    {
        $whitelist = array('Jørgen', 'Hans', 'Inge');
        $rule = new Whitelist('firstname', array('options' => $whitelist));
        $this->assertTrue($rule->execute($this->context, $this->response));
    }
    
    public function testNotInWhitelist()
    {
        $whitelist = array('Hans', 'Inge');
        $rule = new Whitelist('firstname', array('options' => $whitelist));
        $this->assertFalse($rule->execute($this->context, $this->response));
    }
    
    public function testInWhitelistStrict()
    {
        $whitelist = array('Jørgen', 'Hans', 'Inge');
        $rule = new Whitelist('firstname', array('options' => $whitelist, 'strict' => true));
        $this->assertTrue($rule->execute($this->context, $this->response));
    }
    
    public function testNotInWhitelistStrict()
    {
        $whitelist = array('jørgen', 'Hans', 'Inge');
        $rule = new Whitelist('firstname', array('options' => $whitelist, 'strict' => true));
        $this->assertFalse($rule->execute($this->context, $this->response));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingOptionsOptionError()
    {
        $rule = new Whitelist('firstname');
        $rule->execute($this->context, $this->response);
    }
    
    public function testStrictOptionIsOptional()
    {
        try {
            $rule = new Whitelist('firstname', array('options' => array()));
            $rule->execute($this->context, $this->response);
        } catch (\InvalidArgumentException $e) {
            $this->fail();
        }
    }
}