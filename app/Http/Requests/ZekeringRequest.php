<?php

namespace App\Http\Requests;

use App\Models\Zekering;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ZekeringRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => ['required', 'max:150'],
            // A reply is to a top-level zekering, which has room for it.
            'parent_id' => [
                'nullable',
                'bail',
                'integer',
                Rule::exists('zekerings', 'id')->whereNull('parent_id')->withoutTrashed(),
                function (string $attribute, int $parentId, Closure $fail) {
                    if (Zekering::query()->where('parent_id', $parentId)->count() >= Zekering::MAX_CHILDREN) {
                        $fail('This zekering has no room for more sub-zekeringen.');
                    }
                },
            ],
        ];
    }
}
