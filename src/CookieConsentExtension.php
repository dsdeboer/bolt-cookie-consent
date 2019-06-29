<?php

namespace Bolt\Extension\Charpand\CookieConsent;

use Bolt\Asset\File\JavaScript;
use Bolt\Asset\File\Stylesheet;
use Bolt\Asset\Snippet\Snippet;
use Bolt\Asset\Target;
use Bolt\Extension\SimpleExtension;
use Silex\Application;
use Twig_Environment;

/**
 * CookieConsent extension class.
 */
class CookieConsentExtension extends SimpleExtension
{
    /**
     * {@inheritdoc}
     */
    protected function registerTwigPaths()
    {
        return [
            'templates' => ['position' => 'prepend', 'namespace' => 'bolt'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultConfig()
    {
        return [
            'templates'                 => [
                'script' => '@bolt/_cookie_consent.twig',
            ],
            'ignore-styling'            => false,
            'href'                      => '',
            'theme'                     => 'edgeless',
            'position'                  => 'top',
            'palette-popup-background'  => '#383b75',
            'palette-popup-text'        => '#ffffff',
            'palette-button-background' => '#f1d600',
            'path'                      => '/',
            'domain'                    => '',
            'expiryDays'                => 365,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerServices(Application $app)
    {
        $app['twig'] = $app->extend(
            'twig',
            function (Twig_Environment $twig) use ($app) {
                $config = $this->getConfig();

                $consent = new CookieConsent($app, $config);
                $twig->addGlobal('consent', $consent);

                return $twig;
            }
        );
    }

    protected function registerAssets(): array
    {
        return [
            Javascript::create('cookieconsent.min.js')->setPriority(980)->setLate(true),
            Stylesheet::create('cookieconsent.min.css')->setPriority(990)->setLate(true),
            Stylesheet::create('bootstrap.cookieconsent.min.css')->setPriority(991)->setLate(true),
        ];
    }
}
