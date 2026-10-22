<?php

namespace App\Enums;

/**
 * The types of field on a registration form.
 */
enum FormFieldType: string
{
    case Text = 'text';
    case Number = 'number';
    case Textarea = 'textarea';
    case Checkbox = 'checkbox';
    case Select = 'select';
    case Radio = 'radio';
    case Checkboxes = 'checkboxes';

    /**
     * Whether the field has options to choose from.
     */
    public function hasOptions(): bool
    {
        return in_array($this, [self::Select, self::Radio, self::Checkboxes]);
    }
}
