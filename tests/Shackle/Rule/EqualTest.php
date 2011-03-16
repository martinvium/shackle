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

class EqualTest extends TestCase 
{
    public function testEqual()
    {
        $rule = new Equal('firstname', array('equal' => 'Jørgen'));
        $this->assertTrue($rule->execute($this->context, $this->response));
    }
    
    public function testNotEqual()
    {
        $rule = new Equal('firstname', array('equal' => 'Jørgenx'));
        $this->assertFalse($rule->execute($this->context, $this->response));
    }
    
    public function testGetLastResponse_NoExecute_ReturnsNull()
    {
        $rule = new Equal('firstname', array('equal' => 'Jørgenx'));
        $this->assertNull($rule->getLastResponse());
    }

    public function testGetLastResponse_NoResponseArg_ReturnsNewResponse()
    {
        $rule = new Equal('firstname', array('equal' => 'Jørgenx'));
        $rule->execute($this->context);
        $this->assertInstanceOf('VXML\Response', $rule->getLastResponse());
    }

    public function testGetLastResponse_PassedResponse_ReturnsSameResponse()
    {
        $rule = new Equal('firstname', array('equal' => 'Jørgenx'));
        $rule->execute($this->context, $this->response);
        $this->assertSame($this->response, $rule->getLastResponse());
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingEqualOptionError()
    {
        $rule = new Equal('firstname');
        $rule->execute($this->context, $this->response);
    }
}