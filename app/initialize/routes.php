<?php
// Routes

$route_files = array_diff(scandir(__DIR__ . '/../routes/'), array('.', '..'));
foreach ($route_files as $route_file) {
    require __DIR__ . '/../routes/' . $route_file;
}
