<?php

namespace NeoxNotify\NeoxNotifyBundle\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class neoxNotifyExtension extends AbstractExtension
{

    public function __construct( )
    {
        // Inject dependencies if needed

    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            //new TwigFilter('GetSessionBag', [$this, 'GetSessionBag']),

        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('neox_notify', [$this, 'generateNotifyHtml'], array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )),
        ];
    }
    
    public function generateNotifyHtml(Environment $twig, string $controllerName = "notify", array $topics = []): string
    {
        return $twig->render("Partial/neoxNotify/neox_notify.html.twig", array(
            'controller' => $controllerName,
            'topics' => $topics,
        ));
    }
    
    }