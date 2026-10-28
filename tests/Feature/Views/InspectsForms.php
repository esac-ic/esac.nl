<?php

namespace Tests\Feature\Views;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Testing\TestResponse;

/**
 * Helper for inspecing forms
 */
trait InspectsForms
{
    private function xpath(TestResponse $response): DOMXPath
    {
        $document = new DOMDocument();
        libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8">' . $response->getContent());
        libxml_clear_errors();

        return new DOMXPath($document);
    }

    private function field(TestResponse $response, string $name): DOMElement
    {
        $nodes = $this->xpath($response)->query(sprintf('//form//*[self::input or self::select or self::textarea][@name="%s"]', $name));
        $field = $nodes->item(0);
        $this->assertInstanceOf(DOMElement::class, $field, "No form field named $name");

        return $field;
    }

    /**
     * Asserts a form posts to $action with $method, including the CSRF token and the method spoofing field
     */
    private function assertForm(TestResponse $response, string $method, string $action): void
    {
        $xpath = $this->xpath($response);
        // Some views use a relative action
        $forms = $xpath->query(sprintf('//form[@action="%s" or @action="%s"]', url($action), '/' . ltrim($action, '/')));
        $this->assertSame(1, $forms->length, "No form with action $action");
        $form = $forms->item(0);
        $this->assertInstanceOf(DOMElement::class, $form);

        $method = strtoupper($method);
        $this->assertSame($method === 'GET' ? 'GET' : 'POST', strtoupper($form->getAttribute('method')));

        $hidden = fn (string $name) => $xpath->query(sprintf('.//input[@type="hidden"][@name="%s"]', $name), $form)->item(0);
        if ($method !== 'GET') {
            $this->assertNotNull($hidden('_token'), "Form $action has no CSRF token");
        }
        if (in_array($method, ['PUT', 'PATCH', 'DELETE'])) {
            $this->assertSame($method, $hidden('_method')?->getAttribute('value'), "Form $action doesn't spoof $method");
        }
    }

    private function assertFieldValue(TestResponse $response, string $name, string $value): void
    {
        $field = $this->field($response, $name);
        $actual = $field->tagName === 'textarea' ? $field->textContent : $field->getAttribute('value');
        $this->assertSame($value, $actual, "Field $name");
    }

    private function assertSelected(TestResponse $response, string $name, string $value): void
    {
        $selected = [];
        foreach ($this->field($response, $name)->getElementsByTagName('option') as $option) {
            if ($option->hasAttribute('selected')) {
                $selected[] = $option->getAttribute('value');
            }
        }
        $this->assertSame([$value], $selected, "Selected option of $name");
    }

    private function assertChecked(TestResponse $response, string $name, bool $checked = true): void
    {
        $this->assertSame($checked, $this->field($response, $name)->hasAttribute('checked'), "Checkbox $name");
    }
}
