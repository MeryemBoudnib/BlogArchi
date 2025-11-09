<?php
// Fichier : tests/Traits/UsesDatabaseMigrations.php

// Ceci remplace le comportement de RefreshDatabase par défaut pour éviter le conflit
// avec les transactions manuelles de DB::beginTransaction().

namespace Tests\Traits;

trait UsesDatabaseMigrations
{
    use \Illuminate\Foundation\Testing\DatabaseMigrations;
}