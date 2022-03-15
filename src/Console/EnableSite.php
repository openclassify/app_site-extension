<?php namespace Visiosoft\AppSiteExtension\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputArgument;

class EnableSite extends Command
{
    use DispatchesJobs;

    protected $name = 'site:enable';

    protected $description = 'Enable Reference.';

    public function handle()
    {
        $reference = $this->argument('reference');

        $q = DB::select('select * from applications where reference = "' . $reference . '"');

        if (!count($q)) {
            throw new \Exception('Application reference not found!');
        }

        $app = array_first($q);

        DB::statement("UPDATE applications SET enabled = true where id =" . $app->id);

        $this->info("Enabled $app->reference!");
    }

    protected function getArguments()
    {
        return [
            ['reference', InputArgument::REQUIRED, 'The application reference'],
        ];
    }

    protected function getOptions()
    {
        return [];
    }
}
