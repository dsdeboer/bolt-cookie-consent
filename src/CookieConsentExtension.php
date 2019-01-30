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
            'href'       => '',
            'container'  => '',
            'theme'      => 'block',
            'position'   => 'bottom',
            'palette-popup-background' => '#383b75',
            'palette-button-background' => '#f1d600',
            'path'       => '/',
            'domain'     => '',
            'expiryDays' => 365,
            'custom-html-header' => '<span class="cc-header">{{header}}</span>&nbsp;',
            'custom-html-message' => '<span id="cookieconsent:desc" class="cc-message">{{message}}</span>',
            'custom-html-messageLink' => '<span id="cookieconsent:desc" class="cc-message">{{message}} <a aria-label="learn more about cookies" tabindex="0" class="cc-link" href="{{href}}" target="_blank">{{link}}</a></span>',
            'custom-html-dismiss' => '<a aria-label="dismiss cookie message" tabindex="0" class="cc-btn cc-dismiss">{{dismiss}}</a>',
            'custom-html-allow' => '<a aria-label="allow cookies" tabindex="0" class="cc-btn cc-allow">{{allow}}</a>',
            'custom-html-deny' => '<a aria-label="deny cookies" tabindex="0" class="cc-btn cc-deny">{{deny}}</a>',
            'custom-html-link' => '<a aria-label="learn more about cookies" tabindex="0" class="cc-link" href="{{href}}" target="_blank">{{link}}</a>',
            'custom-html-close' => '<span aria-label="dismiss cookie message" tabindex="0" class="cc-close">{{close}}</span>',
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
        "position": '%position%',
        "container": %container%,
        "theme": '%theme%',
        "content": {
            "link": '%learnMore%',
            "message": '%message%',
            "dismiss": '%dismiss%',
            "href": %href%
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
        },
        "elements": {
            "header": '%custom-html-header%',
            "message": '%custom-html-message%',
            "messagelink": '%custom-html-messageLink%',
            "dismiss": '%custom-html-dismiss%',
            "allow": '%custom-html-allow%',
            "deny": '%custom-html-deny%',
            "link": '%custom-html-link%',
            "close": '%custom-html-close%',
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
            '%palette_button_background%',
            '%custom-html-header%',
            '%custom-html-message%',
            '%custom-html-messageLink%',
            '%custom-html-dismiss%',
            '%custom-html-allow%',
            '%custom-html-deny%',
            '%custom-html-link%',
            '%custom-html-close%'
        ];

        $replaceArray = [
            htmlspecialchars($config['message'], ENT_QUOTES),
            htmlspecialchars($config['dismiss'], ENT_QUOTES),
            htmlspecialchars($config['learnMore'], ENT_QUOTES),
            htmlspecialchars($config['position'], ENT_QUOTES),
            htmlspecialchars($config['palette-popup-background'], ENT_QUOTES),
            htmlspecialchars($config['palette-button-background'], ENT_QUOTES),
            $config['custom-html-header'],
            $config['custom-html-message'],
            $config['custom-html-messageLink'],
            $config['custom-html-dismiss'],
            $config['custom-html-allow'],
            $config['custom-html-deny'],
            $config['custom-html-link'],
            $config['custom-html-close'],
        ];

        if ($config['href'] !== '') {
            $searchArray[] = '%href%';
            $replaceArray[] = "'" . htmlspecialchars($config['href'], ENT_QUOTES) . "'";
        } else {
            $searchArray[] = '%href%';
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
