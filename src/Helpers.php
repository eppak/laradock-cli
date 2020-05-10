<?php

function userHome($path = null): string
{
    $user = posix_getpwuid(posix_getuid());

    if ($path == null) {
        return $user['dir'];
    }

    return "{$user['dir']}/{$path}";
}

function sudo(): bool
{
    return (posix_getuid() == 0);
}
