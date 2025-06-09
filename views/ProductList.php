<?php 
/**
 * Product List View
 * This file displays the product catalog and shopping cart interface.
 * Features:
 * - Country/Currency selection
 * - Product listing with prices in selected currency
 * - Shopping cart management
 * - Order confirmation
 */

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Services\CurrencyService;

// Initialize currency and location settings from session
$conversionRate = $_SESSION['conversion_rate'] ?? 1;
$currencyInfo = $_SESSION['currency_info'] ?? ['code' => 'USD', 'symbol' => '$'];
$currencyCode = $currencyInfo['code'];
$currencySymbol = $currencyInfo['symbol'];
$country = $_SESSION['country'] ?? 'USA';
$countryCurrency = CurrencyService::$countryCurrency;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Table</title>
    <link rel="stylesheet" type="text/css" href="views/css/ProductList.css">
</head>
<body>

<!-- Country Selection Form -->
<div class="country-select">
    <form method="POST" action="index.php">
        <label for="country">Select Country:</label>
        <select name="country" id="country">
            <?php foreach ($countryCurrency as $ctry => $info): ?>
                <option value="<?= $ctry ?>" <?= $ctry === $country ? 'selected' : '' ?>>
                    <?= $ctry ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Set Country">
    </form>
</div>

<!-- Product Listing Table -->
<table>
    <tr><th>Name</th><th>Description</th><th>Price</th><th>Action</th></tr>
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= $currencySymbol ?> <?= number_format($row['converted_price'], 2) ?></td>
                <td>
                    <!-- Add to Cart Form -->
                    <form method="post" action="add-to-cart">
                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                        <input type="number" name="quantity" value="1" min="1">
                        <input type="submit" value="Add to Cart">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="4">No products found</td></tr>
    <?php endif; ?>
</table>

<!-- Shopping Cart Section -->
<?php if (!empty($_SESSION['cart'])): ?>
    <h2>Your Cart</h2>
    <table>
        <tr><th>Product</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr>
        
        <?php
        // Calculate cart totals with currency conversion
        $total = 0;
        foreach ($_SESSION['cart'] as $item):
            $price = $item['price'] * $conversionRate;
            $subtotal = $price * $item['quantity'];
            $total += $subtotal;
        ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= $currencySymbol ?> <?= number_format($price, 2) ?></td>
                <td><?= $currencySymbol ?> <?= number_format($subtotal, 2) ?></td>
            </tr>
        <?php endforeach; ?>
        
        <!-- Cart Total -->
        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td><strong><?= $currencySymbol ?> <?= number_format($total, 2) ?></strong></td>
        </tr>
    </table>
    
    <!-- Order Confirmation Form -->
    <form method="post" action="confirm-order" style="margin-top: 20px; text-align: right; margin-right: 127px">
        <input type="submit" value="Confirm Order">
    </form>
<?php endif; ?>

<!-- Order Confirmation Message -->
<?php if (isset($_SESSION['order_confirmed'])): ?>
    <p style="color: green;">Order placed successfully!</p>
    <?php unset($_SESSION['order_confirmed']); ?>
<?php endif; ?>

</body>
</html>
