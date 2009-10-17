<?php
use VXML\Rule;

require_once 'VXML/Context.php';
require_once 'VXML/Response.php';
require_once 'VXML/Translator/Gettext.php';

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

$data = array(
	'ekstra1' => 153,
	'ekstra2' => 0,
	'ekstra3' => false,
	'ekstra4' => array(1, 2, 3),
	'ekstra5' => array('a' => 123, 'b' => 432),
	'b_year'  => 1984,
	'b_month' => 10,
	'b_day'   => 10,
	'email'   => 'test43@test.dk'
);

$data['ekstra6'][] = array('email' => 'test1@test.dk', 'firstname' => 'bla1');
$data['ekstra6'][] = array('email' => 'test2@test.dk', 'firstname' => 'bla2');

// TODO allow for autocompletion in included document, we register contexts within a static stack? 
// $set = VXML::getParentRule(); ?
echo '<pre>';
$set = new Rule\Set();

// simple
$set->add(new Rule\NumberRange('ekstra1', array('min' => 100, 'max' => 300, 'message' => 'ekstra1 failed (number range)')));

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
$set2->add(new Rule\Equals('b', array('equals' => 432, 'message' => 'field b is invalid')));

// forward + iterate
$set3 = $set->add(new Rule\Iterate(new Rule\Set('ekstra6', array('min' => 1))));
$set3->add(new Rule\Equals('email', array('equals' => 'test2@test.dk', 'message' => 'ekstra6 failed (email is invalid)')));
$set3->add(new Rule\Equals('firstname', array('equals' => 'bla2', 'message' => 'ekstra6 failed (firstname is invalid)')));
$set3->add(new Rule\Equals('/ekstra1', array('equals' => 153, 'message' => 'ekstra6 failed (absolute test)')));

// composite rules
$set->add(new Rule\Person\Birthdate(array('b_year', 'b_month', 'b_day'), array('message' => 'birthdate failed')));

// use a rule only for chaining (no error)
$chainer = $set->add(new Rule\Silence(new Rule\Equals('b_month', array('equals' => 9, 'message' => 'blabla'))));
$chainer->addListener('valid', new Rule\Equals('/ekstra1', array('equals' => 154, 'message' => 'msg')));
$chainer->addListener('invalid', new Rule\Equals('/ekstra1', array('equals' => 153, 'message' => 'msg')));

// execute
$response = new VXML\Response();
$ret = $set->execute(new VXML\Context($data), $response);


// apply a remote ruleset into a local context
// TODO is there any way to redefine targets in the remote ruleset? do we have to or can we just use naming conventions?
//$set->add(new Rule\Include('ekstra6', null, array('path' => 'test2.php', 'map' => array('remap' => 'targets'))));

// re-map values according to an array of values
//$set->addListener('pre', new Rule\Filter_Map('ekstra1', array('map' => array('1' => 'Ja', '2' => 'Nej'))));
//$set->addListener('post', new Rule\Filter_Map('ekstra2', array('map' => $campaign->strings->get('something'))));

//$set->add()

// callback
//$cb_rule = $set->add(new Rule\Callback('ekstra3[]', null, array('callback' => function($event) {
//	// TODO use an Event object for passing to rules, this way we can pass 
//	// the parent object, context, etc without updating existing methods?
//	$ctx = $event->getContext()->getPassedValue();
//	$resp = $event->getResponse();
//	$event->addError();
//	$event->getParentRule(); // ? Rule\Abstract : null;
//	
//	// old
//	var_dump($context->getPassedValue(), (bool)$context->getPassedValue());
//	$response->addError('fisk');
//	$this->invoke();
//	return (bool)$context->getPassedValue();
//})));

echo '<table border="1" cellpadding="3">';
foreach($response->getAllMessages() as $msg)
{
	echo '<tr style="background-color: ' . get_color_from_type($msg['type']) . '">';
	echo '<td>' . write_target($msg['target']) . '</td>';
	echo '<td>' . get_class($msg['rule']) . '</td>';
	echo '<td>' . $msg['message'] . '</td>';
	echo '<td>' . $msg['debug'] . '</td>';
	echo '</tr>';
}
echo '<tr><td colspan="3">Result</td><td style="background-color: ' . ($ret ? '#008000' : '#FF2020') . '">' . ($ret ? 'TRUE' : 'FALSE') . '</td></tr>';
echo '</table>';

function get_color_from_type($type)
{
	return ($type == \VXML\Response::MSG_SUCCESS ? '#008000' : ($type == \VXML\Response::MSG_DEBUG ? '#DFDFDF' : '#FF2020'));
}

function write_target($target)
{
	if(is_array($target))
	{
		return implode(',', $target);
	}
	else 
	{
		return $target;
	}
}

function cb_rule_success()
{
	echo 'cb_rule_success';
}