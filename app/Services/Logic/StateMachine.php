<?php

namespace App\Services\Logic;


/**

$machine = new StateMachine\FiniteStateMachine();
$machine->addEvent('start', array('parked' => 'idling'));
$machine->addEvent('drive', array('idling' => 'driving'));
$machine->addEvent('stop', array('driving' => 'idling'));
$machine->addEvent('park', array('idling' => 'parked'));
$machine->setInitialState('parked');

-----------------

$machine->start();
echo $machine->getCurrentStatus();
// prints "idling"

$machine->drive();
echo $machine->getCurrentStatus();
// prints "driving"

 */

trait StateMachine
{
    /**
     * the current state of this machine
     * @var TheTwelve\Techne\State
     */
    private $state;

    /**
     * a list of available events
     * @var unknown_type
     */
    protected $events;

    /**
     * (non-PHPdoc)
     * @see TheTwelve\Techne.StateMachine::getCurrentState()
     */
    public function getCurrentState()
    {
        return $this->state;
    }

    /**
     * (non-PHPdoc)
     * @see TheTwelve\Techne.StateMachine::addEvent()
     */
    public function addEvent($name, array $transitions)
    {
        $this->events[$name] = $transitions;
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see TheTwelve\Techne.StateMachine::setInitialState()
     */
    public function setInitialState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * catch messages passed to this machine and treat them as events
     * @param string $name
     * @param array $arguments
     * @throws InvalidEventException
     * @throws InvalidTransitionException
     */
    public function __call($name, $arguments)
    {
        if (!array_key_exists($name, $this->events)) {
            throw new Techne\InvalidEventException(
                'Event [' . $name . '] does not exist'
            );
        }

        $transitions = $this->events[$name];

        if (!array_key_exists((string)$this->state, $transitions)) {
            throw new Techne\InvalidTransitionException(
                'Machine cannot transition from '
                . '[' . $this->state . '] after [' . $name . ']'
            );
        }

        // changes the state
        // by th model in private method
        $this->state = $transitions[(string)$this->state];
    }
}
