<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_title_en', '');
        $this->migrator->add('general.site_title_bn', '');
        $this->migrator->add('general.site_tagline_en', '');
        $this->migrator->add('general.site_tagline_bn', '');
        $this->migrator->add('general.contacts_en', '');
        $this->migrator->add('general.contacts_bn', '');
        $this->migrator->add('general.emails_en', '');
        $this->migrator->add('general.emails_bn', '');
        $this->migrator->add('general.ads_en', '');
        $this->migrator->add('general.ads_bn', '');
        $this->migrator->add('general.menu_links_en', '');
        $this->migrator->add('general.menu_links_bn', '');
        $this->migrator->add('general.social_links_en', '');
        $this->migrator->add('general.social_links_bn', '');
        $this->migrator->add('general.footer_links_en', '');
        $this->migrator->add('general.footer_links_bn', '');
    }
};
