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

    public function label(): string
    {
        return match ($this) {
            self::Text => 'Text',
            self::Number => 'Number',
            self::Textarea => 'Text area',
            self::Checkbox => 'Checkbox',
            self::Select => 'Select',
            self::Radio => 'Radio buttons',
            self::Checkboxes => 'Checkboxes',
        };
    }

    /**
     * Whether the field has options to choose from.
     */
    public function hasOptions(): bool
    {
        return in_array($this, [self::Select, self::Radio, self::Checkboxes]);
    }
}
