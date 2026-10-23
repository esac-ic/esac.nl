<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerAction;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/**
 * Rich text from the editor, stored without scripts, event handlers, forms or other markup that could run code;
 * unknown tags from pasted text are removed but their text is kept.
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
            // Text pasted from other sites carries unknown tags; keep their content but not the tags.
            ->defaultAction(HtmlSanitizerAction::Block)
            ->dropElement('script')
            ->dropElement('style')
            ->dropElement('noscript')
            ->dropElement('template')
            ->allowElement('font', ['color', 'face', 'size'])
            ->allowElement('iframe', ['src', 'width', 'height', 'frameborder', 'allowfullscreen'])
            // The editor sets colours, line heights and image sizes inline, and Bootstrap classes on tables.
            ->allowAttribute('style', '*')
            ->allowAttribute('class', '*')
            ->allowRelativeLinks()
            ->allowMediaSchemes(['https', 'http'])
            ->allowRelativeMedias()
            ->withMaxInputLength(-1)))->sanitize($html);
    }
}
