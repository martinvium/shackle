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