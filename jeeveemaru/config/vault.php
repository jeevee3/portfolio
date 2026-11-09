<?php

return [
    'path'    => env('VAULT_PATH'),
    'hash'    => base64_decode((string) env('VAULT_HASH_B64')),
    'timeout' => 15,
];