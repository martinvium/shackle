<?php
/**
 * Copyright 2011 Martin Vium
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Shackle\Rule;

class IteratorTest extends TestCase 
{
    public function testIteratableTarget()
    {
        $rule = new Iterator(new Regex('friends', array('pattern' => '/@/')));
        $this->assertTrue($rule->execute($this->context, $this->response));
    }
    
    public function testIterableTargetsFailure()
    {
        $rule = new Iterator(new Regex('blocked', array('pattern' => '/@/')));
        $this->assertFalse($rule->execute($this->context, $this->response));
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testSingleTarget()
    {
        $rule = new Iterator(new Regex('email', array('pattern' => '/@/')));
        $rule->execute($this->context, $this->response);
    }
    
    public function testMinimumOptionSuccess()
    {
        $rule = new Iterator(new Regex('blocked', array('pattern' => '/@/')), array('min' => 1));
        $this->assertTrue($rule->execute($this->context, $this->response));
    }
    
    public function testMinimumOptionTooFew()
    {
        $rule = new Iterator(new Regex('blocked', array('pattern' => '/@/')), array('min' => 2));
        $this->assertFalse($rule->execute($this->context, $this->response));
    }
    
    public function testMaximumOptionSuccess()
    {
        $rule = new Iterator(new Regex('blocked', array('pattern' => '/@/')), array('min' => 1, 'max' => 1));
//        $this->assertEquals(array(), array_map(function ($msg) {return $msg['type']; }, $this->response->getAllMessages()));
        $this->assertTrue($rule->execute($this->context, $this->response));
    }
    
    public function testMaximumOptionFailure()
    {
        $rule = new Iterator(new Regex('friends', array('pattern' => '/@/')), array('max' => 1));
        $this->assertFalse($rule->execute($this->context, $this->response));
    }
}