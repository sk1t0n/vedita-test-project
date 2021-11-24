<?php require 'products.php';

exit();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Товары</title>
  <link rel="stylesheet" href="./dist/main.css">
  <script type="text/javascript" src="./js/index.js"></script>
</head>

<body>
  <main class="container">
    <h1>Товары</h1>

    <?php
    $i = 0;
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
    if (0 === count($products)) {
        echo '<div class="alert">Товары не найдены</div>';

        exit();
    }
    ?>

    <table class="products">
      <tr>
        <th>№</th>
        <th>ID товара</th>
        <th>Название товара</th>
        <th>Цена товара</th>
        <th>Артикул товара</th>
        <th>
          <span>Количество товара</span>
          <?php
          $newPageCount = Pagination::getPageCount($totalProducts, $limit + 1);
          $disabledPlus = $page > $newPageCount || $page === $pageCount ? 'small-button_disabled' : '';
          $disabledMinus = 1 === $limit ? 'small-button_disabled' : '';
          ?>
          <button class="small-button <?php echo $disabledPlus; ?>" onclick='handlerButtonPlus(<?php echo "{$page},{$limit}"; ?>)'>+</button>
          <button class="small-button <?php echo $disabledMinus; ?>" onclick='handlerButtonMinus(<?php echo "{$page},{$limit}"; ?>)'>-</button>
        </th>
        <th>Дата изготовления</th>
        <th></th </tr>
        <?php foreach ($products as $product) { ?>
      <tr>
        <?php ++$i; ?>
        <td><?php echo $i; ?></td>
        <td><?php echo $product[1]; ?></td>
        <td><?php echo $product[2]; ?></td>
        <td><?php echo $product[3]; ?></td>
        <td><?php echo $product[4]; ?></td>
        <td><?php echo $product[5]; ?></td>
        <td><?php echo $product[6]; ?></td>
        <td><button class="button" onclick="handlerHideProduct(<?php echo $product[0]; ?>)">Скрыть</button></td>
      </tr>
    <?php } ?>
    </table>
    <nav class="pagination">
      <ul>
        <?php
        if (1 === $pageCount) {
            exit();
        }
        for ($i = 1; $i <= $pageCount; ++$i) {
            ?>
          <li>
            <?php
            if ($page === $i) {
                echo "<a class='link link_disabled' href='?page={$i}&limit={$limit}'>{$i}</a>";
            } else {
                echo "<a class='link' href='?page={$i}&limit={$limit}'>{$i}</a>";
            } ?>
          </li>
        <?php
        } ?>
      </ul>
    </nav>
  </main>
</body>

</html>