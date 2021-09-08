<?php
namespace Germania\ClearCache;

use Psr\Cache\CacheItemPoolInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClearCacheCommand extends Command
{

    // Name of the command (the part after "bin/console")
    protected static $defaultName = 'cache:clear';

    /**
     * @var CacheItemPoolInterface[]
     */
    public $psr_cache_pools;

    /**
     * @var string[]
     */
    public $cache_directories;



    public function __construct(array $cache_directories, array $psr_cache_pools)
    {
        $this->psr_cache_pools = $psr_cache_pools;
        $this->cache_directories = $cache_directories;

        parent::__construct();
    }


    // Define a description, help message and the input options and arguments:
    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Clears app cache directory and PSR Cache Item Pools')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command clears the various caches...')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Do not really delete things')
        ;
    }



    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Clear caches');


        $dryrun = $input->getOption('dry-run');


        // --------------------------------------
        // Directories
        // --------------------------------------

        foreach($this->cache_directories as $key => $dir) {
            $this->clearCacheDirectory($dir, $dryrun, $output);
        }

        // --------------------------------------
        // CacheItemPool Services
        // --------------------------------------

        foreach($this->psr_cache_pools as $key => $cache_itempool) {
            $this->clearPsrCacheItemPool($cache_itempool, $key, $dryrun, $output);
        }


        return Command::SUCCESS;
    }




    protected function clearCacheDirectory($dir, $dryrun, $output)
    {
        $output->write(sprintf("Clear Cache Directory: '%s' ... ", $dir));
        try {
            if (!is_dir($dir)) {
                $msg = sprintf("Item '%s' is not a directory", $dir);
                throw new \RuntimeException($msg);
            }
            if (!is_dir($dir)) {
                $msg = sprintf("Item '%s' is not a directory", $dir);
                throw new \RuntimeException($msg);
            }
            $failures = false;
            if (!$dryrun) {
                $di = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
                $ri = new \RecursiveIteratorIterator($di, \RecursiveIteratorIterator::CHILD_FIRST);
                foreach ( $ri as $file ) {
                    if ($file->isDir()) {
                        rmdir($file);
                    }
                    else {
                        if (is_writable($file)) {
                            unlink($file);
                        }
                        else {
                            $failures = true;
                            $output->writeln(sprintf(\PHP_EOL . "Not writeable: %s", $file));
                        }
                    }
                }
            }
            if (!$failures) {
                $output->writeln("<fg=green>OK</>");
            }
            else {
                $output->writeln("<fg=cyan>There were errors, please check file permissions.</>");
            }

        } catch (\Throwable $e) {
            $io->newLine();
            $io->error([
                   get_class($e),
                   $e->getMessage(),
                   sprintf("Line %s in '%s'", $e->getLine(), $e->getFile())
                ]);
        }
    }



    protected function clearPsrCacheItemPool($cacheitempool, $title, $dryrun, $output)
    {
        $output->write(sprintf("Clear PSR Cache ItemPool: '%s' ... ", $title));

        try {
            if (!$cacheitempool instanceof CacheItemPoolInterface) {
                $msg = sprintf("'%s' is not CacheItemPoolInterface instance", $title);
                throw new \RuntimeException($msg);
            }
            if (!$dryrun) {
                $cacheitempool->clear();
            }
            $output->writeln("<fg=green>OK</>");
        } catch (\Throwable $e) {
            $io->newLine();
            $io->error([
                   get_class($e),
                   $e->getMessage(),
                   sprintf("Line %s in '%s'", $e->getLine(), $e->getFile())
                ]);
        }
    }
}
