<?php

namespace App\Settings;

use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;
use Jbtronics\SettingsBundle\Storage\JSONFileStorageAdapter;

#[Settings(storageAdapter: JSONFileStorageAdapter::class)]
class CompanySettings
{
    use SettingsTrait;

    #[SettingsParameter(label: 'app.ui.company_name')]
    public string $name = '';

    #[SettingsParameter(label: 'app.ui.company_code')]
    public string $code = '';

    #[SettingsParameter(label: 'app.ui.company_email')]
    public string $email = '';

    #[SettingsParameter(label: 'app.ui.company_phone')]
    public string $phone = '';

    #[SettingsParameter(label: 'app.ui.bank_account')]
    public string $bankAccount = '';

    #[SettingsParameter(label: 'app.ui.bank_name')]
    public string $bankName = '';
}
