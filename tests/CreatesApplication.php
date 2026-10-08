<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $database = (string) $app['config']->get('database.connections.mysql.database');
        if ($database === '' || !str_ends_with($database, '_testing')) {
            throw new \RuntimeException(
                "PHPUnit bloqueado: la base de datos '{$database}' no es una base exclusiva de pruebas terminada en _testing."
            );
        }

        return $app;
    }
}
