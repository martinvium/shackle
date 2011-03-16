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