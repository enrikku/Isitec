<?php

require_once __DIR__ . '\..\utils/utils.php';

$data = json_decode(file_get_contents('php://input'), true);

$courseId = $data['courseId'];

guardarDislike($courseId);

