<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

/**
 * Caps an image's pixels, which decide the memory its conversions need, whatever its shape.
 */
class MaxMegapixels implements ValidationRule
{
    public function __construct(private readonly int $megapixels) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        [$width, $height] = $value instanceof UploadedFile ? (getimagesize($value->getRealPath()) ?: [0, 0]) : [0, 0];

        if ($width * $height > $this->megapixels * 1_000_000) {
            $fail("The :attribute can have at most {$this->megapixels} megapixels, e.g. 6000×4000.");
        }
    }
}
