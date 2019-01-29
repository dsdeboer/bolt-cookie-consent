<?php

namespace Bolt\Extension\Leskis\CookieConsent;

use Bolt\Asset\File\Stylesheet;
use Bolt\Asset\Snippet\Snippet;
use Bolt\Asset\File\JavaScript;
use Bolt\Controller\Zone;
use Bolt\Asset\Target;
use Bolt\Extension\SimpleExtension;

/**
 * CookieConsent extension class.
 */
class CookieConsentExtension extends SimpleExtension
{

    protected function getDefaultConfig()
    {
        return [
            'message'    => 'This website uses cookies to ensure you get the best experience on our website',
            'dismiss'    => 'Got it!',
            'learnMore'  => 'More info',
            'link'       => '',
            'container'  => '',
            'theme'      => 'block',
            'position'   => 'bottom',
            'palette-popup-background' => '#383b75',
            'palette-button-background' => '#f1d600',
            'path'       => '/',
            'domain'     => '',
            'expiryDays' => 365
        ];
    }

    protected function registerAssets()
    {
        return [
            (new JavaScript('cookieconsent.min.js'))->setZone(Zone::FRONTEND)->setLate(true)->setPriority(998),
            (new Stylesheet('cookieconsent.min.css'))->setZone(Zone::FRONTEND)->setLate(true)->setPriority(997),
            (new Snippet())->setCallback([$this, 'cookieConsentSnippet'])->setZone(Zone::FRONTEND)->setLocation(Target::AFTER_BODY_JS)->setPriority(996),
        ];
    }

    public function cookieConsentSnippet()
    {
        $config = $this->getConfig();

        $html = <<< EOM
<script>
window.addEventListener("load", function(){
    window.cookieconsent.initialise({
        "showLink": '%learnMore%',
        "position": '%position%',
        "container": %container%,
        "theme": '%theme%',
        "content": {
            "message": '%message%',
            "dismiss": '%dismiss%',
            "link": %link%
        },
        "cookie": {
            "path": '%path%',
            "domain": '%domain%',
            "expiryDays": %expiryDays%
        },
        "palette": {
            "popup": {
                "background": "%palette_popup_background%"
            },
            "button": {
                "background": "%palette_button_background%"
            }
        }
    })
});
</script>
EOM;
        $searchArray = [
            '%message%',
            '%dismiss%',
            '%learnMore%',
            '%position%',
            '%palette_popup_background%',
            '%palette_button_background%'
        ];

        $replaceArray = [
            htmlspecialchars($config['message'], ENT_QUOTES),
            htmlspecialchars($config['dismiss'], ENT_QUOTES),
            htmlspecialchars($config['learnMore'], ENT_QUOTES),
            htmlspecialchars($config['position'], ENT_QUOTES),
            htmlspecialchars($config['palette-popup-background'], ENT_QUOTES),
            htmlspecialchars($config['palette-button-background'], ENT_QUOTES),
        ];

        if ($config['link'] !== '') {
            $searchArray[] = '%link%';
            $replaceArray[] = "'" . htmlspecialchars($config['link'], ENT_QUOTES) . "'";
        } else {
            $searchArray[] = '%link%';
            $replaceArray[] = 'null';
        }

        if ($config['container'] !== '') {
            $searchArray[] = '%container%';
            $replaceArray[] = "'" . htmlspecialchars($config['container'], ENT_QUOTES) . "'";
        } else {
            $searchArray[] = '%container%';
            $replaceArray[] = 'null';
        }

        $searchArray = array_merge($searchArray, [
            '%theme%',
            '%path%',
            '%domain%',
            '%expiryDays%'
        ]);

        $replaceArray = array_merge($replaceArray, [
            htmlspecialchars($config['theme'], ENT_QUOTES),
            htmlspecialchars($config['path'], ENT_QUOTES),
            htmlspecialchars($config['domain'], ENT_QUOTES),
            $config['expiryDays']
        ]);

        $html = str_replace($searchArray, $replaceArray, $html);
        return new \Twig_Markup($html, 'UTF-8');
    }
}
