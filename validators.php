<?php
use VXML\Rule;

// decorators
require_once 'VXML/Rule/Optional.php';
require_once 'VXML/Rule/Iterate.php';
require_once 'VXML/Rule/Silence.php';

// rules
require_once 'VXML/Rule/NumberRange.php';
require_once 'VXML/Rule/Callback.php';
require_once 'VXML/Rule/Equals.php';
require_once 'VXML/Rule/Import.php';
require_once 'VXML/Rule/Person/Birthdate.php';
require_once 'VXML/Rule/Set.php';
require_once 'VXML/Rule/Regex.php';
require_once 'VXML/Rule/Filter/Replace.php';

$set = Rule\Import::getInstance();
$set->add(new Rule\NumberRange('ekstra1', array('min' => 100, 'max' => 300, 'message' => 'number range test failed')));

// optional
$ekstra2 = $set->add(new Rule\Optional(new Rule\NumberRange('ekstra2', array('min' => 100, 'max' => 300, 'message' => 'ekstra2 failed (optional number range)'))));
$ekstra2->addListener('optional', new Rule\Equals('/ekstra1', array('equals' => 153, 'message' => 'ekstra1 failed (optional callback)')));

// set
$set2 = $set->add(new Rule\Set('.', array('min' => 1)));
$set2->add(new Rule\Regex('email', array('pattern' => '/@/', 'message' => 'regex failed')));
$set2->add(new Rule\Regex('email', array('pattern' => '/^@/', 'message' => 'regex failed')));

// iterate
$set->add(new Rule\Iterate(new Rule\Equals('ekstra4', array('equals' => 2, 'message' => 'ekstra4 failed (iterator equals)')), array('min' => 1)));

// forward target
$set2 = $set->add(new Rule\Set('ekstra5'));
$rule_a = $set2->add(new Rule\Equals('a', array('equals' => 123, 'message' => 'field a is invalid')));
$rule_a->addListener('valid', 'cb_rule_success');

// forward + iterate
$set->add(new Rule\Import('ekstra6', array('path' => 'validators2.php')));
//$set3 = $set->add(new Rule\Iterate(new Rule\Set('ekstra6', array('min' => 1))));
//$set3->add(new Rule\Equals('email', array('equals' => 'test2@test.dk', 'message' => 'ekstra6 failed (email is invalid)')));
//$set3->add(new Rule\Equals('firstname', array('equals' => 'bla2', 'message' => 'ekstra6 failed (firstname is invalid)')));
//$set3->add(new Rule\Equals('/ekstra1', array('equals' => 153, 'message' => 'ekstra6 failed (absolute test)')));

// composite rules
$set->add(new Rule\Person\Birthdate(array('b_year', 'b_month', 'b_day'), array('message' => 'birthdate failed')));

// use a rule only for chaining (no error)
$chainer = $set->add(new Rule\Silence(new Rule\Equals('b_month', array('equals' => 9, 'message' => 'blabla'))));
$chainer->addListener('valid', new Rule\Equals('/ekstra1', array('equals' => 154, 'message' => 'msg')));
$chainer->addListener('invalid', new Rule\Equals('/ekstra1', array('equals' => 153, 'message' => 'msg')));

$set->add(new Rule\Callback('email', array('callback' => 'cb_rule_test', 'message' => 'rimelig')));

// TODO apply a remote ruleset into a local context
//$set->add(new Rule\Import('ekstra6', null, array('path' => 'test2.php', 'map' => array('remap' => 'targets'))));

// TODO trim field and replace values
//$set->addListener('pre', new Rule\Filter\Trim('ekstra7'));
//$set->addListener('pre', new Rule\Filter\Replace('ekstra7', array('search' => 'Ja', 'replace' => 'Nej')));
//$set->addListener('post', new Rule\Filter\Replace('ekstra7', array('search' => 'Nej', 'replace' => 'Ja')));
//$set->add(new Rule\Silence(new Rule\Equals('ekstra7', array('equals' => 'Ja'))))->addListener('valid', new Rule\Filter\Value('.', array('value' => 'Nej')));
//$set->add(new Rule\Silence(new Rule\Equals('ekstra7', array('equals' => 'Ja'))))->valid(new Rule\Filter\Value('.', array('value' => 'Nej')));

/**
 * @param VXML\Event $event
 */
function cb_rule_success($event)
{
	echo 'cb_rule_success';
}