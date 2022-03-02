<?php namespace Visiosoft\AppSiteExtension\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Visiosoft\AppSiteExtension\SiteInstaller;

class MakeSite extends Command
{
    use DispatchesJobs;

    protected $name = 'make:site';

    protected $description = 'Create a new site.';

    public function handle()
    {
        $name = $this->argument('name');

        if (preg_match('/^[a-z0-9\-]*$/', $name) !== 1) {
            throw new \Exception('Please enter a supported name.');
        }

        $username = $this->option('username') ? $this->option('username') : "admin";
        $email = $this->option('email') ? $this->option('email') : "admin@example.com";
        $password = $this->option('password') ? $this->option('password') : "admin123";
        $domain = $this->option('domain') ? $this->option('domain') : $name . ".ocify.site";
        $locale = $this->option('locale') ? $this->option('locale') : "en";

        (new SiteInstaller())->install($name, $username, $email, $password, $domain, $locale);

        $this->info("Created .env for $name!");
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The application name'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['username', null, InputOption::VALUE_REQUIRED, 'Admin username'],
            ['email', null, InputOption::VALUE_REQUIRED, 'Admin email address'],
            ['password', null, InputOption::VALUE_REQUIRED, 'Admin password'],
            ['domain', null, InputOption::VALUE_REQUIRED, 'Site domain'],
            ['locale', null, InputOption::VALUE_REQUIRED, 'Site locale'],
        ];
    }
}
