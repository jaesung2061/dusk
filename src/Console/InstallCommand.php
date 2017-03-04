<?php

namespace Laravel\Dusk\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dusk:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Dusk into the application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! is_dir($this->generateTestsPath('Browser/screenshots'))) {
            $this->createScreenshotsDirectory();
        }

        if (! is_dir($this->generateTestsPath('Browser/console'))) {
            $this->createConsoleDirectory();
        }

        $subs = [
            'ExampleTest.stub' => $this->generateTestsPath('Browser/ExampleTest.php'),
            'HomePage.stub' => $this->generateTestsPath('Browser/Pages/HomePage.php'),
            'DuskTestCase.stub' => $this->generateTestsPath('DuskTestCase.php'),
            'Page.stub' => $this->generateTestsPath('Browser/Pages/Page.php'),
        ];

        foreach ($subs as $stub => $file) {
            if (! is_file($file)) {
                copy(__DIR__.'/../../stubs/'.$stub, $file);
            }
        }

        $this->info('Dusk scaffolding installed successfully.');
    }

    /**
     * Create the screenshots directory.
     *
     * @return void
     */
    protected function createScreenshotsDirectory()
    {
        mkdir($this->generateTestsPath('Browser/screenshots'), 0755, true);

        file_put_contents($this->generateTestsPath('Browser/screenshots/.gitignore'), '*
!.gitignore
');
    }

    /**
     * Create the console directory.
     *
     * @return void
     */
    protected function createConsoleDirectory()
    {
        mkdir($this->generateTestsPath('Browser/console'), 0755, true);

        file_put_contents($this->generateTestsPath('Browser/console/.gitignore'), '*
!.gitignore
');
    }

    protected function generateTestsPath($path)
    {
        return (config('dusk.tests_path') ?: 'tests').DIRECTORY_SEPARATOR.$path;
    }
}
