<?php

namespace Bolt\Extension\Charpand\CookieConsent;

use Bolt\Asset\File\JavaScript;
use Bolt\Asset\File\Stylesheet;
use Bolt\Asset\Snippet\Snippet;
use Bolt\Asset\Target;
use Bolt\Controller\Zone;
use Bolt\Extension\BobdenOtter\Seo\SEO;
use Bolt\Extension\SimpleExtension;
use Bolt\Translation\Translator as Trans;
use Silex\Application;
use Twig_Environment;
use Twig_Markup;

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
            'templates' => [
                'script' => '@bolt/_cookie_consent.twig',
            ],
            'ignore-styling'            => false,
            'href'                      => '',
            'theme'                     => 'block',
            'position'                  => 'bottom',
            'palette-popup-background'  => '#383b75',
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
            (new JavaScript('cookieconsent.min.js'))->setZone(Zone::FRONTEND)->setLate(true)->setPriority(998),
            (new Stylesheet('cookieconsent.min.css'))->setZone(Zone::FRONTEND)->setLate(true)->setPriority(997),
            (new Snippet())->setCallback([$this, 'cookieConsentSnippet'])->setZone(Zone::FRONTEND)->setLocation(Target::AFTER_BODY_JS)->setPriority(996),
        ];
    }
}
