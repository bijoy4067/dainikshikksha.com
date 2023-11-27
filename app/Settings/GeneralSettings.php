<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public $site_title_en;
    public $site_title_bn;
    public $site_tagline_en;
    public $site_tagline_bn;
    public $contacts_en;
    public $contacts_bn;
    public $emails_en;
    public $emails_bn;
    public $ads_en;
    public $ads_bn;

    public static function group(): string
    {
        return 'general';
    }
}
