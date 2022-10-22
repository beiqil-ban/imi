<?php

declare(strict_types=1);

namespace Imi\Dev;

use FilesystemIterator;
use Imi\Cli\ImiCommand;
use function implode;
use function method_exists;
use function realpath;
use function sprintf;
use Symfony\Component\Process\Process;
use function usleep;

class Plugin
{
    public const MAX_RUNNING = 4;

    public static function dev(): void
    {
        $componentsDir = \dirname(__DIR__) . '/src/Components';
        /** @var Process[] $readyProcesses */
        $readyProcesses = [];
        foreach (new FilesystemIterator($componentsDir, FilesystemIterator::SKIP_DOTS) as $dir)
        {
            if (!$dir->isDir())
            {
                continue;
            }
            $fileName = $dir->getPathname() . '/composer.json';
            if (!is_file($fileName))
            {
                continue;
            }
            $process = self::createUpdateProcess($dir);
            $readyProcesses[$dir->getBasename()] = $process;
        }

        self::parallel($readyProcesses, self::MAX_RUNNING, 'Update <info>%s</info>');
    }

    protected static function createUpdateProcess(\SplFileInfo $dir): Process
    {
        $cmd = [
            \PHP_BINARY,
            realpath($_SERVER['SCRIPT_FILENAME']),
            'update',
            '--no-interaction',
            '--prefer-dist',
            '--no-progress',
        ];
        $p = self::createProcess($cmd);
        $p->setWorkingDirectory($dir->getPathname());

        return $p;
    }

    public static function IDEHelper(): void
    {
        require_once __DIR__ . '/../vendor/autoload.php';

        $output = ImiCommand::getOutput();

        $cmd = [
            \PHP_BINARY,
            __DIR__ . '/../src/Cli/bin/imi-cli',
            'imi/buildRuntime',
            '--app-namespace=Imi\\\\Dev',
        ];
        $process = self::createProcess($cmd);
        $process->run(static function ($type, $buffer) {
            echo $buffer;
        });

        $result = $process->isSuccessful() ? '<info>success</info>' : "<error>fail({$process->getExitCode()})</error>";
        $output->writeln("[Runtime Scan]: {$result}");
    }

    protected static function createProcess(array $cmd): Process
    {
        if (method_exists(Process::class, 'fromShellCommandline'))
        {
            $process = new Process($cmd);
        }
        else
        {
            // 兼容 symfony process < 3.3
            $process = new Process([]);
            // @phpstan-ignore-next-line
            $process->setCommandLine(implode(' ', $cmd));
        }
        $process->setTimeout(0);

        return $process;
    }

    /**
     * @param Process[] $processes
     */
    protected static function parallel(array $processes, int $max, string $titleTemp): void
    {
        $output = ImiCommand::getOutput();
        $running = 0;
        while (\count($processes))
        {
            foreach ($processes as $name => $process)
            {
                $title = sprintf($titleTemp, $name);
                if (!$process->isStarted() && $max > $running)
                {
                    ++$running;
                    $output->writeln("[{$title}]");
                    $process->start(static function ($type, $buffer) {
                        echo $buffer;
                    });
                }
                elseif ($process->isStarted() && !$process->isRunning())
                {
                    --$running;
                    $result = $process->isSuccessful() ? '<info>success</info>' : "<error>fail({$process->getExitCode()})</error>";
                    $output->writeln("[{$title}]: {$result}");
                    unset($processes[$name]);
                }
            }
            usleep(1000);
        }
    }
}
