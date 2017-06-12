<?php
namespace AppBundle\Extension\Twig;

use AppBundle\Util\PlaceHolders;
use Symfony\Component\DependencyInjection\Container;

class CustomTwigExtension extends \Twig_Extension
{
    protected $placeholderReplace;
    protected $container;

    public function __construct(Container $container, $placeholderReplace)
    {
        $this->container = $container;
        $this->placeholderReplace = $placeholderReplace;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('numberBanglaConvert', array($this, 'numberBanglaConvert'))
        );
    }

    public function getFunctions() {

        return array(
            new \Twig_SimpleFunction('numberBanglaConvert', array($this, 'numberBanglaConvert'), array('is_safe'=>array('html'))),
        );
    }


    public function numberBanglaConvert($number)
    {
        return PlaceHolders::numberConvert($number);
    }


    public function getName()
    {
        return 'custom_twig_extension';
    }
} 