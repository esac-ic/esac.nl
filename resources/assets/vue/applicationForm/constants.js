//api
export const FORM_TYPE_SELECT = 'select';
export const FORM_TYPE_RADIO = 'radio';
export const FORM_TYPE_TEXT_BOX = 'textarea';
export const FORM_TYPE_CHECK_BOX = 'checkbox';
export const FORM_TYPE_CHECK_BOXES = 'checkboxes';
export const FORM_TYPE_NUMBER = 'number';
export const FORM_TYPE_TEXT = 'text';

export const FORM_TYPES = {
    [FORM_TYPE_TEXT]: "Text",
    [FORM_TYPE_NUMBER]: "Number",
    [FORM_TYPE_CHECK_BOX]: "Checkbox",
    [FORM_TYPE_TEXT_BOX]: "Text area",
    [FORM_TYPE_SELECT]: "Select",
    [FORM_TYPE_RADIO]: "Radio buttons",
    [FORM_TYPE_CHECK_BOXES]: "Checkboxes"
};

export const ROW_OPTION_FORM_TYPE = [
    FORM_TYPE_CHECK_BOXES,
    FORM_TYPE_RADIO,
    FORM_TYPE_SELECT
];