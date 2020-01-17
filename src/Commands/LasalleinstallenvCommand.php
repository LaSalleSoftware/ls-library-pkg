<?php

/**
 * This file is part of the Lasalle Software library.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019-2020 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 *
 * @see       https://lasallesoftware.ca
 * @see       https://packagist.org/packages/lasallesoftware/lsv2-library-pkg
 * @see       https://github.com/LaSalleSoftware/lsv2-library-pkg
 */

namespace Lasallesoftware\Library\Commands;

// LaSalle Software class
use Illuminate\Console\ConfirmableTrait;
// Laravel classes
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Lasallesoftware\Library\Common\Commands\CommonCommand;

/**
 * Class LasalleinstallpartoneCommand.
 *
 * First of two artisan command installation scripts
 */
class LasalleinstallenvCommand extends CommonCommand
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lslibrary:lasalleinstallenv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'LaSalle Software environment variables installation.';

    /**
     * Create a new config command instance.
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // -------------------------------------------------------------------------------------------------------------
        // START: INTRO
        echo "\n\n";
        $this->info('================================================================================');
        $this->info('                  lslibrary:lasalleinstallenv ');
        $this->info('================================================================================');
        echo "\n\n";

        $this->line('--------------------------------------------------------------------------------');
        $this->line('                       Welcome to my LaSalle Software\'s');
        $this->line('               Environment Variables Installation Artisan Command!');
        $this->line('--------------------------------------------------------------------------------');
        $this->line('  You are installing the '.mb_strtoupper(env('LASALLE_APP_NAME')).' LaSalle Software Application.');
        echo "\n";
        $this->line('  You are installing to your '.$this->getLaravel()->environment().' environment.');
        $this->line('--------------------------------------------------------------------------------');
        $this->line('  This command sets some variables in your .env.');
        $this->line('--------------------------------------------------------------------------------');
        $this->line('  Read my INSTALLATION.md *BEFORE* running this command.');
        $this->line('--------------------------------------------------------------------------------');
        $this->line('  If you are installing my administrative back-end app, then run this ');
        $this->line('  artisan command FIRST, then run lslibrary:lasalleinstalladminapp SECOND.');
        $this->line('--------------------------------------------------------------------------------');
        $this->line('  I have two separate installation artisan commands because there are lots of');
        $this->line('  times where my environment variable updates are not recognized by my database');
        $this->line('  seeding. So after much angst, I put my .env updates in one artisan command,');
        $this->line('  and the database migration and seeding in another separate artisan command,');
        $this->line("  and it's been happiness ever since.");
        $this->line('--------------------------------------------------------------------------------');
        echo "\n";
        $this->line('  Thank you for installing my LaSalle Software!');
        $this->line('  --Bob Bloom');
        $this->line('--------------------------------------------------------------------------------');
        // END: INTRO
        // -------------------------------------------------------------------------------------------------------------

        // -------------------------------------------------------------------------------------------------------------
        // START: ARE YOU SURE YOU WANT TO RUN THIS COMMAND?
        echo "\n\n\n";
        $this->line('--------------------------------------------------------------------------------');
        $this->line('  Please confirm that you really want to run this installation command');
        $this->line('--------------------------------------------------------------------------------');
        echo "\n";
        $this->alert('Are you sure that you want to run this command?');
        $runConfirmation = $this->ask('<fg=yellow>(type the word "yes" to continue)</>');
        if ($runConfirmation != strtolower('yes')) {
            $this->line('<fg=red;bg=yellow>OK, you did not type "yes", so I am NOT going to continue running this command. Bye!</>');
            $this->echoOutro();

            return;
        }
        $this->comment('ok... you said that you want to continue running this command. Let us continue then...');
        // END: ARE YOU SURE YOU WANT TO RUN THIS COMMAND?
        // -------------------------------------------------------------------------------------------------------------

        // -------------------------------------------------------------------------------------------------------------
        // START: APP_KEY
        // as a convenience, if for some reason the APP_KEY is not set (in .env), then just go ahead and set it.
        if ('' == env('APP_KEY')) {
            $this->call('key:generate');
        }
        // END: APP_KEY
        // -------------------------------------------------------------------------------------------------------------

        // -------------------------------------------------------------------------------------------------------------
        // START: SET THE PARAMS IN .ENV
        // SET APP_NAME
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  Now setting your APP_NAME environment variable');
        $this->line('-----------------------------------------------------------------------');
        echo "\n\n";
        $this->comment("What is your application's name (APP_NAME)?");
        $this->comment('An example is: LaSalle Software Administration App');
        $this->comment(' ');
        $appName = $this->ask('(I do *not* check for syntax or for anything, so please type c-a-r-e-f-u-l-l-y!)');
        $this->comment('You typed "'.$appName.'".');
        $this->comment('Attempting to set APP_NAME in your .env to "'.$appName.'"...');
        $this->writeEnvironmentFileWithNewKey('DummyAppName', $appName, true);
        $this->info("Attempt to modify your env's APP_NAME to ".$appName.' is finished!');

        // SET APP_URL
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  Now setting your APP_URL environment variable');
        $this->line('-----------------------------------------------------------------------');
        echo "\n\n";
        $this->comment("What is your application's full URL (APP_URL)?");
        $this->comment('  * MUST start with "http://" or "https://"');
        $this->comment('  * NO trailing slash!');
        $this->comment('  * example: https://lasallesoftware.ca');
        $this->comment('  * example: https://lasallesoftware.ca:8888');
        $this->comment(' ');
        $appURL = $this->ask('(I do *not* check for syntax or for anything, so please type c-a-r-e-f-u-l-l-y!)');
        $this->comment('Attempting to set APP_URL in your .env to "'.$appURL.'"...');
        $this->writeEnvironmentFileWithNewKey('DummyAppURL', $appURL, false);
        $this->info("Attempt to modify your env's APP_URL to ".$appURL.' is finished!');

        // SET LASALLE_APP_DOMAIN_NAME
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  Now setting your LASALLE_APP_DOMAIN_NAME environment variable');
        $this->line('-----------------------------------------------------------------------');
        echo "\n\n";
        $this->comment('This is done automatically based on your APP_URL.');

        $lasalleAppDomainName = $this->getLasalleAppDomainName($appURL);
        $this->comment('Attempting to set LASALLE_APP_DOMAIN_NAME in your .env to "'.$lasalleAppDomainName.'""...');
        $this->writeEnvironmentFileWithNewKey('DummyLasalleAppDomainName', $lasalleAppDomainName, false);
        $this->info("Attempt to modify your env's LASALLE_APP_DOMAIN_NAME to ".$lasalleAppDomainName.' is finished!');

        // SET DB_DATABASE
        if ('adminbackendapp' == env('LASALLE_APP_NAME')) {
            echo "\n\n";
            $this->line('-----------------------------------------------------------------------');
            $this->line('  Now setting your DB_DATABASE environment variable');
            $this->line('-----------------------------------------------------------------------');
            echo "\n\n";
            $this->comment(' ');
            $this->comment('Are you installing with Forge? Then Forge, likely, already updated this');
            $this->comment('environment variable, so just hit ENTER.');
            $appDbDatabase = $this->ask('(What is the name of your database?)');
            $this->comment('Attempting to set DB_DATABASE in your .env to "'.$appDbDatabase.'"...');
            $this->writeEnvironmentFileWithNewKey('DummyDbDatabase', $appDbDatabase, false);
            $this->info("Attempt to modify your env's DB_DATABASE to ".$appDbDatabase.' is finished!');
        }

        // SET DB_USERNAME
        if ('adminbackendapp' == env('LASALLE_APP_NAME')) {
            echo "\n\n";
            $this->line('-----------------------------------------------------------------------');
            $this->line('  Now setting your DB_USERNAME environment variable');
            $this->line('-----------------------------------------------------------------------');
            echo "\n\n";
            $this->comment(' ');
            $this->comment('Are you installing with Forge? Then Forge, likely, has already updated');
            $this->comment('this environment variable, so just hit ENTER.');
            $appDbUsername = $this->ask("What is the name of your database's user?");
            $this->comment('Attempting to set DB_USERNAME in your .env to "'.$appDbUsername.'"...');
            $this->writeEnvironmentFileWithNewKey('DummyDbUsername', $appDbUsername, false);
            $this->info("Attempt to modify your env's DB_USERNAME to ".$appDbUsername.' is finished!');
        }

        // SET DB_PASSWSORD
        if ('adminbackendapp' == env('LASALLE_APP_NAME')) {
            echo "\n\n";
            $this->line('-----------------------------------------------------------------------');
            $this->line('  Now setting your DB_PASSWORD environment variable');
            $this->line('-----------------------------------------------------------------------');
            echo "\n\n";
            $this->comment(' ');
            $this->comment('Are you installing with Forge? Then Forge, likely, has already updated');
            $this->comment('this environment variable, so just hit ENTER and then check your .env in Forge.');
            $appDbPassword = $this->ask("What is your database's password?");
            $this->comment('Attempting to set DB_PASSWORD in your .env to "'.$appDbPassword.'"...');
            $this->writeEnvironmentFileWithNewKey('DummyDbPassword', $appDbPassword, false);
            $this->info("Attempt to modify your env's DB_PASSWORD to ".$appDbPassword.' is finished!');
        }

        // LASALLE_JWT_KEY
        if ('basicfrontendapp' == env('LASALLE_APP_NAME')) {
            echo "\n\n";
            $this->line('-----------------------------------------------------------------------');
            $this->line('  Now setting your LASALLE_JWT_KEY environment variable');
            $this->line('-----------------------------------------------------------------------');
            echo "\n\n";
            $this->comment(' ');
            $lasalleJwtKey = Str::random(64);
            $this->comment('Attempting to set LASALLE_JWT_KEY in your .env to "'.$lasalleJwtKey.'"...');
            $this->writeEnvironmentFileWithNewKey('DummyJwtKey', $lasalleJwtKey, false);
            $this->info("Attempt to modify your env's LASALLE_JWT_KEY to ".$lasalleJwtKey.' is finished!');
        }
        // END: SET THE PARAMS IN .ENV
        // -------------------------------------------------------------------------------------------------------------

        // -------------------------------------------------------------------------------------------------------------
        // START: DONE!
        echo "\n\n\n";
        $this->line('--------------------------------------------------------------------------');
        $this->line('        Congratulations! You finished lslibrary:lasalleinstallenv!');
        $this->line('--------------------------------------------------------------------------');

        if ('basicfrontendapp' == env('LASALLE_APP_NAME')) {
            echo "\n\n";
            $this->line('  Please run "php artisan lslibrary:lasalleinstallfrontendapp" to complete your installation');
            $this->line('--------------------------------------------------------------------------');
        }

        if ('adminbackendapp' == env('LASALLE_APP_NAME')) {
            echo "\n\n";
            $this->line('  Please run "php artisan lslibrary:lasalleinstalladminapp" to complete your installation');
            $this->line('--------------------------------------------------------------------------');
        }
        // END: DONE!
        // -------------------------------------------------------------------------------------------------------------
    }

    /**
     * Echo the final message.
     *
     * return void
     */
    protected function echoOutro()
    {
        echo "\n\n";
        $this->info('====================================================================');
        $this->info('              ** lslibrary:lasalleinstallenv has finished **');
        $this->info('====================================================================');
        echo "\n\n";
    }

    /**
     * @param text $patternToSearchFor        The text being searched
     * @param text $envFileDummyKey           The dummy key to be replaced in .env
     * @param bool $useQuotesInTheReplacement Do you want to use quotes in the replacement string?
     */
    protected function writeEnvironmentFileWithNewKey($patternToSearchFor, $envFileDummyKey, $useQuotesInTheReplacement = true)
    {
        $envFile = file_get_contents($this->laravel->environmentFilePath());

        $pattern = $this->pattern($patternToSearchFor);

        $replacement = $useQuotesInTheReplacement ? "'".$envFileDummyKey."'" : $envFileDummyKey;

        $envFile = preg_replace($pattern, $replacement, $envFile);

        file_put_contents($this->laravel->environmentFilePath(), $envFile);
    }

    /**
     * Return the LASALLE_APP_DOMAIN_NAME, which is based on the APP_URL.
     *
     * The APP_URL *must* start with "http://" or "https://". However, if it does not, the APP_URL is returned,
     * just so something is returned.
     *
     * @param text $appURL The APP_URL
     *
     * @return string
     */
    protected function getLasalleAppDomainName($appURL)
    {
        if ('http://' == substr($appURL, 0, 7)) {
            return substr($appURL, 7, strlen($appURL));
        }

        if ('https://' == substr($appURL, 0, 8)) {
            return substr($appURL, 8, strlen($appURL));
        }

        return $appURL;
    }
}
