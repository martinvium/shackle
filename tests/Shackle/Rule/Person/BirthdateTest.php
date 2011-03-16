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
namespace Shackle\Rule\Person;

use Shackle\Rule\TestCase;

/**
 * @todo add support for single string target, maybe just check on 1 vs 3 targets?
 *      we probably should add an option for it, so we don't pass 1 target by accident.
 */
class BirthdateTest extends TestCase
{
    public function testValidValue()
    {
        $targets = array('birthdate_year', 'birthdate_month', 'birthdate_day');
        $rule = new Birthdate($targets);
        $this->assertTrue($rule->execute($this->context, $this->response));
    }
    
    public function testInvalidDay()
    {
        $targets = array('birthdate_year', 'birthdate_month', 'salary');
        $rule = new Birthdate($targets);
        $this->assertFalse($rule->execute($this->context, $this->response));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingTarget()
    {
        $targets = array('birthdate_year', 'birthdate_month');
        $rule = new Birthdate($targets);
        $rule->execute($this->context, $this->response);
    }
}