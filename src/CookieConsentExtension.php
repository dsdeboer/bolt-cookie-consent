<?php

namespace Bolt\Extension\Charpand\CookieConsent;

use Bolt\Asset\File\JavaScript;
use Bolt\Asset\File\Stylesheet;
use Bolt\Extension\SimpleExtension;
use Silex\Application;
use Twig\Markup;

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
    protected function registerTwigFunctions()
    {
        return [
            'cookie_consent' => ['cookieConsent', ['is_safe' => ['html']]],
        ];
    }

    /**
     * The callback function when {{ my_twig_function() }} is used in a template.
     *
     * @return string
     */
    public function cookieConsent()
    {
        $config = $this->getConfig();

        $templateVars = [
            'domain'                    => $config['domain'],
            'href'                      => $config['href'],
            'theme'                     => $config['theme'],
            'position'                  => $config['position'],
            'expiryDays'                => $config['expiryDays'],
            'path'                      => $config['path'],
            'container'                 => $config['container'],
            'palette_button_background' => $config['palette-button-background'],
            'palette_popup_background'  => $config['palette-popup-background'],
            'palette_popup_text'        => $config['palette-popup-text'],
        ];

        $html = $this->renderTemplate('@bolt/cookie_consent.twig', $templateVars);
        return new Markup($html, 'UTF-8');
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultConfig()
    {
        return [
            'templates'                 => [
                'script' => '@bolt/cookie_consent.twig',
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
     * We can share our configuration as a service so our other classes can use it.
     *
     * {@inheritdoc}
     */
    protected function registerServices(Application $app)
    {
        $app['cookie_consent.config'] = $app->share(function ($app) {
            return $this->getConfig();
        });
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
