<?php
namespace VXML\Rule;

use VXML\Response;
use VXML\Event;

final class Invert extends DecoratorAbstract
{
    /**
     * @todo Re-adding rules like this will screw up targets
     * @param Event $event
     */
    protected function evaluate(Event $event)
    {
        $mockResponse = new Response();
        if (! $this->rule->execute($event->getContext(), $mockResponse)) {
            foreach ($mockResponse->getFailureMessages() as $failure) {
                $mockResponse->addSuccess($failure['rule'], $failure['debug']);
            }

            return true;
        } else {
            foreach ($mockResponse->getSuccessMessages() as $success) {
                $event->getResponse()->addFailure($success['rule'], $success['debug']);
            }

            return false;
        }
    }
}