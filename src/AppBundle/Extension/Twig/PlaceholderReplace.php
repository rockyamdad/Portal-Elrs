<?php
namespace AppBundle\Extension\Twig;

use AppBundle\Util\PlaceHolders;

class PlaceholderReplace
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

}