<?php

namespace Modules\LivewireCore\Database\Capsule;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Capsule\Manager as BaseManager;
use Modules\LivewireCore\Database\Connectors\ConnectionFactory;

class Manager extends BaseManager
{
    /**
     * Build the database manager instance.
     *
     * @return void
     */
    protected function setupManager()
    {
        $factory = new ConnectionFactory($this->container);

        $this->manager = new DatabaseManager($this->container, $factory);
    }
}
