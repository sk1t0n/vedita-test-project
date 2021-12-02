<?php

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/database/Products.php';
require_once __DIR__ . '/src/pagination/Pagination.php';

use App\Pagination\Pagination;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

$page = isset($_GET['page'])
    ? filter_var($_GET['page'], FILTER_VALIDATE_INT)
    : 1;
$limit = isset($_GET['limit'])
    ? filter_var($_GET['limit'], FILTER_VALIDATE_INT)
    : 5;
$offset = ($page - 1) * $limit;

$totalProducts = $db->getTotalProducts();
$pageCount = Pagination::getPageCount($totalProducts, $limit);
$products = $db->getProducts($limit, $offset);

$newPageCount = Pagination::getPageCount($totalProducts, $limit + 1);
$disabledPlus = $page > $newPageCount || $page === $pageCount
    ? 'small-button_disabled'
    : '';
$disabledMinus = $limit === 1 ? 'small-button_disabled' : '';

echo $twig->render('index.html.twig', [
    'page' => $page,
    'limit' => $limit,
    'pageCount' => $pageCount,
    'products' => $products,
    'disabledPlus' => $disabledPlus,
    'disabledMinus' => $disabledMinus,
]);
