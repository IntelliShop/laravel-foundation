<?php

declare(strict_types=1);

namespace IntelliShop\LaravelFoundation\Application\Console;

use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Database\Connection;
use Hyn\Tenancy\Traits\MutatesMigrationCommands;
use Illuminate\Console\ConfirmableTrait;
use Laravel\Passport\Console\InstallCommand as OriginalCommand;

final class PassportInstallCommand extends OriginalCommand
{
    use MutatesMigrationCommands, ConfirmableTrait;

    public function __construct(WebsiteRepository $repository, Connection $connection)
    {
        parent::__construct();

        $this->setName('tenancy:'.$this->getName());
        $this->specifyParameters();

        $this->websites = $repository;
        $this->connection = $connection;
    }

    public function handle(): void
    {
        if ($this->confirmToProceed()) {
            $this->input->setOption('force', true);

            $this->processHandle(function ($website): void {
                $this->connection->set($website);

                parent::handle();

                $this->connection->purge();
            });
        }
    }
}
