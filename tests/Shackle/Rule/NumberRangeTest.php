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
namespace VXML\Rule;

class NumberRangeTest extends TestCase 
{
    public function testInRange()
    {
        $rule = new NumberRange('salary', array('min' => 10000, 'max' => 40000));
        $this->assertTrue($rule->execute($this->context, $this->response));
    }
    
    public function testNumberTooHigh()
    {
        $rule = new NumberRange('salary', array('min' => 25001, 'max' => 40000));
        $this->assertFalse($rule->execute($this->context, $this->response));
    }
    
    public function testNumberTooLow()
    {
        $rule = new NumberRange('salary', array('min' => 10000, 'max' => 24999));
        $this->assertFalse($rule->execute($this->context, $this->response));
    }
    
    public function testOnlyMinimumOptionInRange()
    {
        $rule = new NumberRange('salary', array('min' => 10000));
        $this->assertTrue($rule->execute($this->context, $this->response));
    }
    
    public function testOnlyMinimumOptionOutOfRange()
    {
        $rule = new NumberRange('salary', array('min' => 40000));
        $this->assertFalse($rule->execute($this->context, $this->response));
    }
    
    public function testOnlyMaximumOptionInRange()
    {
        $rule = new NumberRange('salary', array('max' => 40000));
        $this->assertTrue($rule->execute($this->context, $this->response));
    }
    
    public function testOnlyMaximumOptionOutOfRange()
    {
        $rule = new NumberRange('salary', array('max' => 10000));
        $this->assertFalse($rule->execute($this->context, $this->response));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingBothMinAndMaxOptions()
    {
        $rule = new NumberRange('salary', array());
        $rule->execute($this->context, $this->response);
    }
}