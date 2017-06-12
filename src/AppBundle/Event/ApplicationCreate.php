<?php
namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class ApplicationCreate extends Event
{

    protected $application;

    public function __construct($application)
    {
        $this->application = $application;
    }

    public function getApplication()
    {
      return $this->application;
    }
}