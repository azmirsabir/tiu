<?php

return [
    'api_route_path'   => 'admin/api/log-reader',
    'view_route_path'  => 'log-reader',
    'admin_panel_path' => 'home',
    'middleware'       => ['web', 'auth','dev_admin']
];
