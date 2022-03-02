<?php namespace Visiosoft\AppSiteExtension;

use Anomaly\Streams\Platform\Support\Collection;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SiteInstaller
{
    use DispatchesJobs;

    public function install($name, $username, $email, $password, $domain, $locale = 'en')
    {
        $data = new Collection();

        $data->put('APP_ENV', 'local');
        $data->put('INSTALLED', 'false');
        $data->put('ADMIN_THEME', 'visiosoft.theme.defaultadmin');
        $data->put('STANDARD_THEME', 'visiosoft.theme.base');
        $data->put('APP_KEY', str_random(32));

        $data->put('APPLICATION_NAME', '"' . $name . '"');
        $data->put('APPLICATION_DOMAIN', $domain);
        $data->put('APPLICATION_REFERENCE', $name);
        $data->put('APP_URL', 'http://' . $domain);

        $data->put('ADMIN_USERNAME', $username);
        $data->put('ADMIN_EMAIL', $email);
        $data->put('ADMIN_PASSWORD', $password);
        $data->put('APP_LOCALE', $locale);

        $contents = '';

        foreach ($data as $key => $value) {
            if ($key) {
                $contents .= strtoupper($key) . '=' . $value . PHP_EOL;
            } else {
                $contents .= $value . PHP_EOL;
            }
        }

        if (!is_dir(base_path('resources/' . $name))) {
            mkdir(base_path('resources/' . $name));
        }

        file_put_contents(base_path('resources/' . $name . '/.env'), $contents);
    }
}
