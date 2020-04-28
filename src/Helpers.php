<?php

function userHome($path = null)
{
    $user = posix_getpwuid(posix_getuid());

    if ($path == null) {
        return $user['dir'];
    }

    return "{$user['dir']}/{$path}";
}
