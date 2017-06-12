<?php

namespace AppBundle\EventListener;

use AppBundle\Event\ApplicationCreate;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ApplicationListener
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onApplicationCreate(ApplicationCreate $appCreate)
    {
        $smsText = ' Apnar abedonti shofol hoyeche . Abedon number '.$appCreate->getApplication()->id.' abong anumanik bitoroner tarikh: '.$appCreate->getApplication()->delivaryDate.' Dhonnobad ';
        $this->container->get('app_bundle.service.sms_manager')->sendSms($appCreate->getApplication()->phone,$smsText);
    }

} 