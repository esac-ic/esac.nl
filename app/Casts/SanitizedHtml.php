<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/**
 * Rich text from the editor, stored with only the markup the editor produces: no scripts, event handlers or forms,
 * and images and embedded videos only from this site, YouTube and Google Drive.
 */
class SanitizedHtml implements CastsInboundAttributes
{
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return $value === null ? null : self::sanitize($value);
    }

    public static function sanitize(string $html): string
    {
        return (new HtmlSanitizer((new HtmlSanitizerConfig)
            ->allowSafeElements()
            ->allowElement('iframe', ['src', 'width', 'height', 'frameborder', 'allowfullscreen'])
            // The editor sets colours, line heights and image sizes inline, and Bootstrap classes on tables.
            ->allowAttribute('style', '*')
            ->allowAttribute('class', '*')
            ->allowRelativeLinks()
            ->allowMediaSchemes(['https', 'http'])
            ->allowMediaHosts([parse_url(config('app.url'), PHP_URL_HOST), 'www.youtube.com', 'youtube.com', 'www.youtube-nocookie.com', 'drive.google.com'])
            ->allowRelativeMedias()
            ->withMaxInputLength(-1)))->sanitize($html);
    }
}
