<?php
$hash = '$2y$10$3UO2b86Y904MPjQQ3Vwo4ubtsjJ3oD39/Rwim3qXQq1NwXvYVqh.m';
$password = 'Simargmbh2012@:';
if (password_verify($password, $hash)) {
    echo "Password matches\n";
} else {
    echo "Password does not match\n";
}
?>