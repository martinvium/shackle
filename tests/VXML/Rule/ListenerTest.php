<?php
namespace VXML\Rule;

class ListenerTest extends TestCase
{
    public function testValidEvent()
    {
        $rule = new Valid();
        $rule->addListener('valid', new Failure());
        $this->assertTrue($rule->execute($this->context, $this->response));
        $this->assertEquals(1, count($this->response->getFailureMessages()));
    }
    
    public function testFailureEvent()
    {
        $rule = new Failure();
        $rule->addListener('failure', new Valid());
        $this->assertFalse($rule->execute($this->context, $this->response));
        $this->assertEquals(1, count($this->response->getFailureMessages()));
        $this->assertEquals(1, count($this->response->getSuccessMessages()));
    }
    
    public function testBeforeEvent()
    {
        $rule = new Failure();
        $rule->addListener('before', new Valid());
        $this->assertFalse($rule->execute($this->context, $this->response));
        $this->assertEquals(1, count($this->response->getFailureMessages()));
        $this->assertEquals(1, count($this->response->getSuccessMessages()));
    }
    
    public function testAfterEvent()
    {
        $rule = new Failure();
        $rule->addListener('after', new Valid());
        $this->assertFalse($rule->execute($this->context, $this->response));
        $this->assertEquals(1, count($this->response->getFailureMessages()));
        $this->assertEquals(1, count($this->response->getSuccessMessages()));
    }
}