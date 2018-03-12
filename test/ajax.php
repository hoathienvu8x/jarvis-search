<?php
header('Content-Type:application/json;charset=utf-8');
require_once realpath('..') . '/macro/init.php';

$res = isset($_GET['question']) ? wiki_get($_GET['question']) : '';

echo json_encode(array('status' => 'success', 'data' => $res));
exit;