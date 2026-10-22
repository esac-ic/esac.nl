<?php

use App\Enums\SettingKey;

return [
    SettingKey::BlockedEmailDomains->value => 'Blocked email domains',
    SettingKey::Killswitch->value => 'Block complete website front-end (0 or 1)',
    SettingKey::FooterPhoneNumber->value => 'Phone number in footer',
    SettingKey::NormalMemberMailLists->value => 'Normal member mail lists',
    SettingKey::ExtraordinaryMemberMailLists->value => 'Extraordinary participant mail lists',
    SettingKey::ReunistMemberMailLists->value => 'Reunist mail lists',
    SettingKey::HonoraryMemberMailLists->value => 'Honorary member mail lists',
    SettingKey::MeritMemberMailLists->value => 'Member of service mail lists',
    SettingKey::TrainerMemberMailLists->value => 'Trainer mail lists',
    SettingKey::RelationshipMemberMailLists->value => 'Relationship member mail lists',
];
