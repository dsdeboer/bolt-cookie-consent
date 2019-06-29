<?php


namespace Bolt\Extension\Charpand\CookieConsent;


use Silex\Application;
use Twig_Markup;

class CookieConsent
{
    /** @var array */
    protected $config;
    /**
     * @var Application
     */
    private $app;

    /**
     * Constructor.
     *
     * @param Application $app
     * @param array $config
     */
    public function __construct(Application $app, $config)
    {
        $this->app    = $app;
        $this->config = $config;
    }

    /**
     *
     * @return Twig_Markup
     */
    public function snippet()
    {
        $html = $this->app['twig']->render($this->config['templates']['script'], [
            'domain'                    => $this->config['domain'],
            'href'                      => $this->config['href'],
            'theme'                     => $this->config['theme'],
            'position'                  => $this->config['position'],
            'expiryDays'                => $this->config['expiryDays'],
            'path'                      => $this->config['path'],
            'container'                 => $this->config['container'],
            'palette_button_background' => $this->config['palette-button-background'],
            'palette_popup_background'  => $this->config['palette-popup-background'],
            'palette_popup_text'        => $this->config['palette-popup-text'],

        ]);

        return new Twig_Markup($html, 'UTF-8');
    }
}