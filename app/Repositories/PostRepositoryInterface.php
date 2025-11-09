<?php

namespace App\Repositories;

interface PostRepositoryInterface
{
    public function create(array $data);
    // ... on pourrait ajouter ici getAll(), findById(), update(), delete() ...
}