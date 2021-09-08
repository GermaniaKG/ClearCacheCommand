<?php
namespace tests;

use Germania\ClearCache\ClearCacheCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCacheCommandTest extends \PHPUnit\Framework\TestCase
{
    public function testInstantiation() : ClearCacheCommand
    {
        $directories = array();
        $psr_cache_pools = array();

        $sut = new ClearCacheCommand($directories, $psr_cache_pools);
        $this->assertInstanceOf(ClearCacheCommand::class, $sut);

        return $sut;
    }
}
