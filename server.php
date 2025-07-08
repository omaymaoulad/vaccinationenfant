<?php
$port = $_ENV['PORT'] ?? 8080;
passthru("php -S 0.0.0.0:$port -t public");