<?php
declare(strict_types=1);

use App\Repository\IssueRepository;
use App\Repository\UserRepository;

return [
    'repositories' => [
        IssueRepository::class,
        UserRepository::class,
    ],
];
