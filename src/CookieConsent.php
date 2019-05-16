<?php


namespace Bolt\Extension\Charpand\CookieConsent;


use Silex\Application;
use Twig_Markup;
use Bolt\Translation\Translator as Trans;

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
            'domain'     => $this->config['domain'],
            'href'       => $this->config['href'],
            'theme'      => $this->config['theme'],
            'position'   => $this->config['position'],
            'expiryDays' => $this->config['expiryDays'],
            'path'       => $this->config['path'],
            'container'  => $this->config['container'],
        ]);

        return new Twig_Markup($html, 'UTF-8');
    }
}