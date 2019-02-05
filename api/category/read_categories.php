<?php
// include database and object files
include_once 'config/core.php';
include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/category.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$category = new Category($db);

// set page title
$page_title="Read Categories";

// include page header HTML
include_once "layout_header.php";

$stmt=$category->readAll_WithPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// to identify page for paging
$page_url="read_categories.php?";

// include categories HTML table template
include_once "read_categories_template.php";

// include page footer HTML
include_once "layout_footer.php";
?>