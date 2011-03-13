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

class ImportTest extends TestCase 
{
    public function testImportPHPFile()
    {
        $rule = new Import('.', array('path' => __DIR__ . '/_files/import.php'));
        $this->assertTrue($rule->execute($this->context, $this->response));
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingPathOption()
    {
        $rule = new Import('.', array());
        $rule->execute($this->context, $this->response);
    }
    
    public function testCompositeFeatures()
    {
        $this->markTestIncomplete('Test add, min, max etc');
    }
}