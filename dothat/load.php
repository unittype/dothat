<?php
define(DB_HOST, 'localhost');
define(DB_PORT, '5432');
define(DB_PASSWD, '9891');
define(DB_USER, 'postgres');
define(DB_NAME, 'dothat');

$db = pg_connect('host='.DB_HOST.' port='.DB_PORT.' password='.DB_PASSWD.' user='.DB_USER.' dbname='.DB_NAME.'');

$sql = 'select id, username, password from admin';

$process = pg_query($db, $sql);

$arr = array();

$row = pg_fetch_all($process);
foreach ($row as $key => $value) {
	array_push($arr, $value);
}
print_r($arr);
?>