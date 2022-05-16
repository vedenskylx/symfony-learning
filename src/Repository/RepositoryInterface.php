<?php

namespace App\Repository;

use App\Dto\AbstractListDto;

/**
 * Class RepositoryInterface
 * @package App\Repository
 */
interface RepositoryInterface
{
    public function search(string $q, int $offset = 0, int $limit = 20): AbstractListDto;
}
