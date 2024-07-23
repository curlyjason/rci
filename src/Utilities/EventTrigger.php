<?php


namespace App\Utilities;


use Cake\Event\Event;
use PHPUnit\Framework\MockObject\MockObject;

trait EventTrigger
{
    /**
     * Dispatch the named Event with the provided Subject object
     *
     * Controllers that call this can pass a mock event by using Dependency Injection
     * <pre>
     *  public function controllerCallPoint(Event $event) {
     *      //code
     *      $this->trigger('eventName', ['data'], $event);
     *      //more code
     *  }
     * </pre>
     * @param $name string
     * @param $data array|object|null
     * @param Event|MockObject $event
     * @return Event|MockObject
     */
    public function trigger(string $name, $data, $event = null): Event|MockObject
    {
        if (is_object($data)) {
            $data = [$data];
        }
        if ($this->isNotMockEvent($event)) {
            $event = new Event($name, $this, $data);
        }
        $this->getEventManager()->dispatch($event);
        return $event;
    }

    /**
     * Is the event a testing mock?
     *
     * @param Event $event
     * @return bool
     */
    public function isNotMockEvent(?Event $event)
    {
        return is_null($event) || !$event instanceof MockObject;
    }

}
