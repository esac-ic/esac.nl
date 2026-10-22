<?php

namespace App\Enums;

/**
 * The kinds of question on a registration form.
 */
enum FormRowType: string
{
    case Text = 'text';
    case Number = 'number';
    case TextBox = 'textBox';
    case CheckBox = 'checkbox';
    case Select = 'select';
    case Radio = 'radio';
    case MultiCheckBox = 'checkboxen';

    /**
     * Whether the question has options to choose from.
     */
    public function hasOptions(): bool
    {
        return in_array($this, [self::Select, self::Radio, self::MultiCheckBox]);
    }
}
