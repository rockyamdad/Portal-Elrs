<?php
namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class UserCreate extends Event
{

    protected $userInfo;

    public function __construct($userInfo)
    {
        $this->userInfo = $userInfo;
    }

    public function getUserInfo()
    {
      return $this->userInfo;
    }
}