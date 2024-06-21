<?php


namespace App\Utilities;


use Cake\Event\Event;

trait EventTrigger
{
    /**
     * Dispatch the named Event with the provided Subject object
     *
     * @param $name string
     * @param $data array|object|null
     * @return Event
     */
    public function trigger(string $name, $data): Event
    {
        if (is_object($data)) {
            $data = [$data];
        }
        $event = new Event($name, $this, $data);
        $this->getEventManager()->dispatch($event);
        return $event;
    }

}
