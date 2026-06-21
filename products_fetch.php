<?php
include 'includes/session.php';

$limit = 9; // Number of items per page
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int) $_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$conn = $pdo->open();

$output = ['products' => '', 'pagination' => ''];

try {
    // Query total products count
    $count_stmt = $conn->query("SELECT COUNT(*) FROM products");
    $total_products = $count_stmt->fetchColumn();
    $total_pages = ceil($total_products / $limit);

    // Fetch page-specific products
    $stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $products_html = '';
    foreach ($stmt as $row) {
        $image = (!empty($row['photo'])) ? 'images/' . $row['photo'] : 'images/noimage.jpg';
        $products_html .= "
        <div class='col'>
          <div class='box box-solid h-100 d-flex flex-column'>
            <div class='product-image-container'>
              <img src='" . $image . "' class='img-responsive w-100 h-100 object-fit-cover' alt='Product Image'>
            </div>
            <div class='prod-info-wrap flex-grow-1 d-flex flex-column justify-content-between'>
              <h5><a href='product.php?product=" . $row['slug'] . "'>" . $row['name'] . "</a></h5>
            </div>
            <div class='box-footer mt-auto d-flex justify-content-between align-items-center'>
              <b class='prod-price'>&#8377; " . number_format($row['price'], 2) . "</b>
              <div class='d-flex gap-1'>
                <a href='product.php?product=" . $row['slug'] . "' class='btn btn-primary btn-xs btn-flat'>View</a>
                <button type='button' class='btn btn-success btn-xs btn-flat add-to-cart-btn' data-id='" . $row['id'] . "'><i class='fa-solid fa-cart-shopping'></i> Add</button>
              </div>
            </div>
          </div>
        </div>
        ";
    }
    $output['products'] = $products_html;

    $pagination_html = '';
    if ($total_pages > 1) {
        $pagination_html .= "<nav aria-label='Product Page Navigation' class='my-5'>";
        $pagination_html .= "<ul class='pagination justify-content-center'>";
        
        $prev_class = ($page <= 1) ? 'disabled' : '';
        $prev_page = $page - 1;
        $pagination_html .= "
            <li class='page-item {$prev_class}'>
              <a class='page-link page-click' href='#' data-page='{$prev_page}' aria-label='Previous'>
                <span aria-hidden='true'>&laquo;</span>
              </a>
            </li>
        ";

        $start_page = max(1, $page - 2);
        $end_page = min($total_pages, $page + 2);
        for ($i = $start_page; $i <= $end_page; $i++) {
            $active_class = ($page == $i) ? 'active' : '';
            $pagination_html .= "
              <li class='page-item {$active_class}'>
                <a class='page-link page-click' href='#' data-page='{$i}'>{$i}</a>
              </li>
            ";
        }

        $next_class = ($page >= $total_pages) ? 'disabled' : '';
        $next_page = $page + 1;
        $pagination_html .= "
            <li class='page-item {$next_class}'>
              <a class='page-link page-click' href='#' data-page='{$next_page}' aria-label='Next'>
                <span aria-hidden='true'>&raquo;</span>
              </a>
            </li>
        ";

        $pagination_html .= "</ul>";
        $pagination_html .= "</nav>";
    }
    $output['pagination'] = $pagination_html;

} catch (PDOException $e) {
    $output['error'] = 'Database error: ' . $e->getMessage();
}

$pdo->close();

header('Content-Type: application/json');
echo json_encode($output);
?>
