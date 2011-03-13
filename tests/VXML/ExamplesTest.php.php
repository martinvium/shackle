<?php
namespace VXML;

use VXML\Rule\NumberRange;
use VXML\Rule\Set;
use VXML\Rule\Equal;
use VXML\Rule\Regex;
use VXML\Rule\Optional;
use VXML\Rule\Invert;

class ExamplesTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleRule()
    {
        $data = array('field_name' => 200);

        $rule = new NumberRange('field_name', array(
            'min' => 100,
            'max' => 300,
            'message' => 'failed'
        ));

        $ret = $rule->execute(new Context($data), new Response());
        $this->assertTrue($ret);
    }

    public function testCombineRulesInSets()
    {
        $data = array('field_name' => 'value', 'field_name2' => '22');

        $set = new Set();
        $set->add(new Equal('field_name', array('equal' => 'value')));
        $set->add(new Regex('field_name2', array('pattern' => '/[0-9]{8}/')));

        $ret = $set->execute(new Context($data), new Response());
        $this->assertFalse($ret);
    }

    public function testDecorateRulesToChangeBehavior_Optional()
    {
        $data = array('field_name' => ''); // empty

        $rule = new Optional(new Equal('field_name', array('equal' => 'value')));

        $ret = $rule->execute(new Context($data), new Response());
        $this->assertTrue($ret);
    }

    public function testDecorateRulesToChangeBehavior_Invert()
    {
        $data = array('field_name' => 'wrong');

        $rule = new Invert(new Equal('field_name', array('equal' => 'value')));

        $ret = $rule->execute(new Context($data), new Response());
        $this->assertTrue($ret);
    }

    public function testTargetMultidimensionalData()
    {
        $data = array('friend' => array(
            'email' => 'value'
        ));

        $rule = new Equal('friend/email', array('equal' => 'value'));

        $ret = $rule->execute(new Context($data), new Response());
        $this->assertTrue($ret);
    }
}