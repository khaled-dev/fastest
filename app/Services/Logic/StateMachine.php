<?php

namespace App\Services\Logic;


use Exception;

/**
 *
 * Set up states
 * Change state and make action for each state
 *
 * $machine->addEvent('start', array('parked' => 'idling'), function ($test) {
 *    $test;
 * });
 *
 * $machine = new StateMachine\FiniteStateMachine();
 * $machine->addEvent('start', array('parked' => 'idling'));
 * $machine->addEvent('drive', array('idling' => 'driving'));
 * $machine->addEvent('stop', array('driving' => 'idling'));
 * $machine->addEvent('park', array('idling' => 'parked'));
 * $machine->setInitialState('parked');
 *
 * --------------------------------------------
 *
 * $machine->start();
 * echo $machine->getCurrentStatus();
 * // prints "idling"
 *
 * $machine->drive();
 * echo $machine->getCurrentStatus();
 * // prints "driving"
 */

trait StateMachine
{
    /**
     * the current state of this machine.
     *
     * @var string
     */
    private $state;

    /**
     * a list of available event actions.
     *
     * @var array
     */
    private $eventAction;

    /**
     * a list of available events.
     *
     * @var array
     */
    protected $events;

    /**
     * Set the initial state of this object.
     *
     * @return string
     */
    public function getCurrentState()
    {
        return $this->state;
    }

    /**
     * Set the initial state of this object.
     *
     * @return $this
     */
    public function addEvent($name, array $transitions, callable $callableAction)
    {
        $this->events[$name] = $transitions;
        $this->eventAction[$name] = $callableAction;
        return $this;
    }

    /**
     * Set the initial state of this object.
     *
     * @return $this
     */
    public function setInitialState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * catch messages passed to this machine and treat them as events.
     *
     * @param string $name
     * @param array $arguments
     * @throws Exception
     * @throws
     */
    public function __call(string $name, array $arguments)
    {
        if (!array_key_exists($name, $this->events)) {
            throw new Exception(
                'Event [' . $name . '] does not exist'
            );
        }

        $transitions = $this->events[$name];

        if (!array_key_exists((string)$this->state, $transitions)) {
            throw new Exception(
                'Machine cannot transition from '
                . '[' . $this->state . '] after [' . $name . ']'
            );
        }

        // changes the state
        // by th model in private method
        $this->state = $transitions[(string)$this->state];
    }
}
