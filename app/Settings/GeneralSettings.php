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
    public $menu_links_en;
    public $menu_links_bn;
    public $social_links_en;
    public $social_links_bn;
    public $footer_links_en;
    public $footer_links_bn;

    public static function group(): string
    {
        return 'general';
    }

    public function mount()
    {
        abort_unless(request()->user()->can('view_custom_page'), 403);
    }
}
