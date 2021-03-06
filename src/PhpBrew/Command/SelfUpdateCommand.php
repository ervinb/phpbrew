<?php
namespace PhpBrew\Command;
use Exception;
use RuntimeException;
use CLIFramework\Command;

class SelfUpdateCommand extends Command
{
    public function usage()
    {
        return 'phpbrew self-update [branch name]';
    }

    public function brief()
    {
        return 'Self-update, default to master version';
    }

    public function execute($branch = 'master')
    {
        global $argv;
        $script = realpath($argv[0]);

        if (!is_writable($script)) {
            throw new Exception("$script is not writable.");
        }

        // fetch new version phpbrew
        $this->logger->info("Updating phpbrew $script from $branch...");
        $url = "https://raw.githubusercontent.com/phpbrew/phpbrew/$branch/phpbrew";
        $code = system("curl -# -L $url > $script");
        if(! $code == 0) {
            throw new RuntimeException("Update Failed", 1);
        }

        $this->logger->info("Version updated.");
        system($script . ' init');
        system($script . ' --version');
    }
}
