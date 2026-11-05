<?php

if (PHP_SAPI !== 'cli' && realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === realpath(__FILE__)) {
    http_response_code(404);
    exit;
}

function dropSetupTables(mysqli $database, string $schemaSql): void
{
    preg_match_all(
        '/CREATE\s+TABLE(?:\s+IF\s+NOT\s+EXISTS)?\s+`((?:``|[^`])+)`/i',
        $schemaSql,
        $matches
    );
    $schemaTables = array_fill_keys(
        array_map(static fn(string $table): string => str_replace('``', '`', $table), $matches[1]),
        true
    );

    if (!$schemaTables) {
        throw new RuntimeException('No table definitions were found in the setup schema.');
    }

    $existingTables = $database->query('SHOW TABLES');
    if (!$existingTables instanceof mysqli_result) {
        throw new RuntimeException('Unable to list database tables.');
    }

    $tablesToDrop = [];
    while ($row = $existingTables->fetch_row()) {
        if (isset($schemaTables[$row[0]])) {
            $tablesToDrop[] = '`' . str_replace('`', '``', $row[0]) . '`';
        }
    }
    $existingTables->free();

    if (!$tablesToDrop) {
        return;
    }

    $database->query('SET FOREIGN_KEY_CHECKS = 0');
    try {
        $database->query('DROP TABLE IF EXISTS ' . implode(', ', $tablesToDrop));
    } finally {
        $database->query('SET FOREIGN_KEY_CHECKS = 1');
    }
}
