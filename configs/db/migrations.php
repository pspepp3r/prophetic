<?php

return [

    'all_or_nothing' => true,
    'check_database_platform' => true,
    'transactional' => 'true',
    'organize_migrations' => 'none',
    'connection' => null,
    'em' => null,

    'table_storage' => [

        'table_name' => 'doctrine_migration_versions',
        'version_column_name' => 'version',
        'version_column_length' => 1024,
        'executed_at_column_name' => 'executed_at',
        'execution_time_column_name' => 'executed_time',

    ],

    'migrations_paths' => [

        'Migrations' => ROOT_DIR . '/storage/cache/orm/migrations'

    ],

];
