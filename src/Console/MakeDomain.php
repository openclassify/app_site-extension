<?php namespace Visiosoft\AppSiteExtension\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeDomain extends Command
{
    use DispatchesJobs;

    protected $name = 'make:domain';

    protected $description = 'Create a new domain.';

    public function is_valid_domain_name($domain_name)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)); //length of each label
    }

    public function handle()
    {
        $domain = $this->argument('domain');

        if (!$this->is_valid_domain_name($domain)) {
            throw new \Exception('Please enter a supported domain.');
        }

        if (!$this->option('reference')) {
            throw new \Exception('Application reference is required! ( --reference=xxxx )');
        }

        $q = DB::select('select * from applications where reference = "' . $this->option('reference') . '"');

        if (!count($q)) {
            throw new \Exception('Application reference not found!');
        }

        $app = array_first($q);

        DB::insert('insert into applications_domains (application_id, domain, locale) values (' . $app->id . ', "' . $domain . '", "en")');

        $this->info("Added domain for $app->reference!");
    }

    protected function getArguments()
    {
        return [
            ['domain', InputArgument::REQUIRED, 'The application domain'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['reference', null, InputOption::VALUE_REQUIRED, 'Application reference'],
        ];
    }
}
