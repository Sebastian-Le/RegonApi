<?php

declare(strict_types=1);

namespace RegonApi;

include_once('vendor/autoload.php');

$regon = new RegonApi();

$nip = $argv[1] ?? 5261040828;

print_r(json_decode($regon->getCompanyDetailsFromNIP((int)$nip)));
