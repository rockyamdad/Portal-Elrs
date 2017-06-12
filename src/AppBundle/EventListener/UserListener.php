<?php

namespace AppBundle\EventListener;

use AppBundle\Event\UserCreate;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserListener
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onUserCreate(UserCreate $userCreate)
    {
        $smsText = ' Apni DRRS a shofol vabe nibondhito hoyechen, apnar beboharkari ID nij mobile number abong password : '.$userCreate->getUserInfo()['password']  .' Dhonnobad ';
        $this->container->get('app_bundle.service.sms_manager')->sendSms($userCreate->getUserInfo()['phoneNumber'],$smsText);
    }

} 