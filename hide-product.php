<?php

require_once __DIR__ . '/src/database/Products.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $db->hideProduct($id);

    http_response_code(200);
} else {
    http_response_code(404);
}
