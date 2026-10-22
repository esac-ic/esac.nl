<?php

namespace Tests\Concerns;

use App\Enums\Role;
use App\Models\ApplicationForm\ApplicationForm;
use App\Models\User;

trait ManagesApplicationForms
{
    public static function managingRoles(): array
    {
        return [
            'content administrator' => [Role::ContentAdministrator],
            'activity administrator' => [Role::ActivityAdministrator],
        ];
    }

    public static function otherRoles(): array
    {
        return [
            'administrator' => [Role::Administrator],
            'certificate administrator' => [Role::CertificateAdministrator],
        ];
    }

    private function actingAsRole(Role ...$roles): void
    {
        $this->actingAs(User::factory()->withRole(...$roles)->create());
    }

    private function assertApplicationForm(ApplicationForm $applicationForm, array $data): void
    {
        $this->assertEquals($data['name'], $applicationForm->name);
        $this->assertCount(count($data['rows']), $applicationForm->applicationFormRows);

        foreach ($applicationForm->applicationFormRows as $i => $row) {
            $rowData = $data['rows'][$i];

            $this->assertEquals($rowData['name'], $row->name);
            $this->assertEquals($rowData['type'], $row->type);
            $this->assertEquals(array_key_exists('required', $rowData), $row->required);

            $options = $row->applicationFormRowOptions->map->only('value', 'name')->all();
            $this->assertEquals(array_map(fn ($option) => ['value' => $option['value'], 'name' => $option['name']], $rowData['options'] ?? []), $options);
        }
    }
}
