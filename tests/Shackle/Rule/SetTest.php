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

use VXML\Context;

class SetTest extends TestCase 
{
    public function testDefaultTargetIsRelative()
    {
        $set = new Set();
        $this->assertEquals(Context::RELATIVE, $set->getRelativeTarget());
    }
    
    public function testCustomTarget()
    {
        $set = new Set('firstname');
        $this->assertEquals('firstname', $set->getRelativeTarget());
    }
    
    public function testEmptyRuleset()
    {
        $set = new Set();
        $this->assertTrue($set->execute($this->context, $this->response));
    }
    
    public function testMinimumDefaultsToMax()
    {
        $set = new Set();
        $set->add(new Equal('firstname', array('equal' => 'Jørgen')));
        $set->add(new Equal('lastname', array('equal' => 'Fisk')));
        $this->assertFalse($set->execute($this->context, $this->response));
    }
    
    public function testMinimumOption()
    {
        $set = new Set(Context::RELATIVE, array('min' => 2));
        $set->add(new Equal('firstname', array('equal' => 'Jørgen')));
        $set->add(new Equal('lastname', array('equal' => 'Fisk')));
        $set->add(new Equal('email', array('equal' => 'jorgen@example.com')));
        $this->assertTrue($set->execute($this->context, $this->response));
    }
    
    public function testMaximumOption()
    {
        $set = new Set(Context::RELATIVE, array('max' => 1));
        $set->add(new Equal('firstname', array('equal' => 'Jørgen')));
        $set->add(new Equal('email', array('equal' => 'jorgen@example.com')));
        $this->assertFalse($set->execute($this->context, $this->response));
    }
    
    public function testNestedSets()
    {
        $set = new Set();
        $set->add(new Equal('firstname', array('equal' => 'Jørgen')));
        
        $nested = new Set();
        $nested->add(new Equal('email', array('equal' => 'jorgen@example.com')));
        $set->add($nested);
        
        $this->assertTrue($set->execute($this->context, $this->response));
    }
    
    public function testNestedSetsFailure()
    {
        $set = new Set();
        $set->add(new Equal('firstname', array('equal' => 'Jørgen')));
        
        $nested = new Set();
        $nested->add(new Equal('email', array('equal' => 'wrong@example.com')));
        $set->add($nested);
        
        $this->assertFalse($set->execute($this->context, $this->response));
    }
}