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