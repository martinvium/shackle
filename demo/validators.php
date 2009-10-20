<?php
use VXML\Rule;

$set = Rule\Import::getInstance();
$set->add(new Rule\NumberRange('ekstra1', array('min' => 100, 'max' => 300, 'message' => 'number range test failed')));

// optional
$ekstra2 = $set->add(new Rule\Optional(new Rule\NumberRange('ekstra2', array('min' => 100, 'max' => 300, 'message' => 'ekstra2 failed (optional number range)'))));
$ekstra2->addListener('optional', new Rule\Equal('/ekstra1', array('equal' => 153, 'message' => 'ekstra1 failed (optional callback)')));

// set
$set2 = $set->add(new Rule\Set('.', array('min' => 1)));
$set2->add(new Rule\Regex('email', array('pattern' => '/@/', 'message' => 'regex failed')));
$set2->add(new Rule\Regex('email', array('pattern' => '/^@/', 'message' => 'regex failed')));

// iterate
$set->add(new Rule\Iterator(new Rule\Equal('ekstra4', array('equal' => 2, 'message' => 'ekstra4 failed (iterator equal)')), array('min' => 1)));

// forward target
$set2 = $set->add(new Rule\Set('ekstra5'));
$rule_a = $set2->add(new Rule\Equal('a', array('equal' => 123, 'message' => 'field a is invalid')));
$rule_a->addListener('valid', 'cb_rule_success');

// forward + iterate
$set->add(new Rule\Import('ekstra6', array('path' => 'validators2.php')));

// composite rules
$set->add(new Rule\Person\Birthdate(array('b_year', 'b_month', 'b_day'), array('message' => 'birthdate failed')));

// use a rule only for chaining (no error)
$chainer = $set->add(new Rule\Silent(new Rule\Equal('b_month', array('equal' => 9, 'message' => 'blabla'))));
$chainer->addListener('valid', new Rule\Equal('/ekstra1', array('equal' => 154, 'message' => 'msg')));
$chainer->addListener('invalid', new Rule\Equal('/ekstra1', array('equal' => 153, 'message' => 'msg')));

$set->add(new Rule\Callback('email', array('callback' => 'cb_rule_test', 'message' => 'rimelig')));

// TODO trim field and replace values
//$set->addListener('pre', new Rule\Filter\Trim('ekstra7'));
//$set->addListener('pre', new Rule\Filter\Replace('ekstra7', array('search' => 'Ja', 'replace' => 'Nej')));
//$set->addListener('post', new Rule\Filter\Replace('ekstra7', array('search' => 'Nej', 'replace' => 'Ja')));
//$set->add(new Rule\Silence(new Rule\Equal('ekstra7', array('equal' => 'Ja'))))->addListener('valid', new Rule\Filter\Value('.', array('value' => 'Nej')));
//$set->add(new Rule\Silence(new Rule\Equal('ekstra7', array('equal' => 'Ja'))))->valid(new Rule\Filter\Value('.', array('value' => 'Nej')));

/**
 * @param VXML\Event $event
 */
function cb_rule_success($event)
{
	echo 'cb_rule_success';
}