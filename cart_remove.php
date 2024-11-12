<?php
session_start();
require 'dbConnection.php';

// Get the user ID
$user_id = $_SESSION['user_id'];

// Get the item ID from the URL parameter
$item_id = isset($_GET['id']) ? intval($_GET['id']) : null; //intval convert the id in integer

// Validate item ID
if (!$item_id) {
    echo "Невалиден идентификатор на продукта.";
    exit;
}

// Check if the item exists in the user's cart
$cart_item_query = "SELECT * FROM users_items WHERE user_id = $user_id AND item_id = $item_id AND status = 'Added to cart'";
$cart_item_result = mysqli_query($conn, $cart_item_query);

if (!$cart_item_result || mysqli_num_rows($cart_item_result) == 0) {
    echo "Продуктът не е намерен в количката.";
    exit;
}

// Remove the item from the cart
$remove_from_cart_query = "DELETE FROM users_items WHERE user_id = ? AND item_id = ? AND status = 'Added to cart'";
$statement = mysqli_prepare($conn, $remove_from_cart_query);

if ($statement) {
    mysqli_stmt_bind_param($statement, 'ii', $user_id, $item_id);
    if (mysqli_stmt_execute($statement)) {
        header('Location: cart.php');
        exit();
    } else {
        echo "Грешка при премахване на продукта от количката: " . mysqli_error($conn);
    }
} else {
    echo "Грешка при подготовката на изявлението: " . mysqli_error($conn);
}

mysqli_stmt_close($statement);
mysqli_close($conn);
?>
