<?php

namespace Tests\Concerns;

use App\Enums\AdminRole;
use App\Models\RegistrationForm;
use App\Models\User;

trait ManagesRegistrationForms
{
    private function actingAsRole(AdminRole ...$roles): void
    {
        $this->actingAs(User::factory()->withRole(...$roles)->create());
    }

    private function assertRegistrationForm(RegistrationForm $registrationForm, array $data): void
    {
        $registrationForm->load('fields.options');
        $this->assertEquals($data['name'], $registrationForm->name);
        $this->assertCount(count($data['rows']), $registrationForm->fields);

        foreach ($registrationForm->fields as $i => $row) {
            $rowData = $data['rows'][$i];

            $this->assertEquals($rowData['name'], $row->name);
            $this->assertEquals($rowData['type'], $row->type);
            $this->assertEquals(array_key_exists('required', $rowData), $row->required);

            $options = $row->options->map->only('value', 'name')->all();
            $this->assertEquals(array_map(fn ($option) => ['value' => $option['value'], 'name' => $option['name']], $rowData['options'] ?? []), $options);
        }
    }
}
