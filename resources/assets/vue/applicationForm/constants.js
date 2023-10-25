//api
export const FORM_TYPE_SELECT = 'select';
export const FORM_TYPE_RADIO = 'radio';
export const FORM_TYPE_TEXT_BOX = 'textBox';
export const FORM_TYPE_CHECK_BOX = 'checkbox';
export const FORM_TYPE_CHECK_BOXEN = 'checkboxen';
export const FORM_TYPE_NUMBER = 'number';
export const FORM_TYPE_TEXT = 'text';

export const FORM_TYPES = {
    [FORM_TYPE_TEXT]: "Text (Single Line)",
    [FORM_TYPE_NUMBER]: "Number",
    [FORM_TYPE_CHECK_BOX]: "Checkbox ",
    [FORM_TYPE_TEXT_BOX]: "Textbox (Multi Line)",
    [FORM_TYPE_SELECT]: "Select (Dropdown)",
    [FORM_TYPE_RADIO]: "Radio (Single Choice)",
    [FORM_TYPE_CHECK_BOXEN]: "Checkbox (Multiple Choice)"
};

export const ROW_OPTION_FORM_TYPE = [
    FORM_TYPE_CHECK_BOXEN,
    FORM_TYPE_RADIO,
    FORM_TYPE_SELECT
];