<?php
$lifetime = 60*60*24*14;
session_set_cookie_params($lifetime, '/');
session_start();

empty($_SESSION['cart12']) ? $_SESSION['cart12'] = array() : '';

// Bảng các sản phẩm
$products = array();
$products['MMS-1754'] = array('name' => 'Flute', 'cost' => '149.50');
$products['MMS-6289'] = array('name' => 'Trumpet', 'cost' => '199.50');
$products['MMS-3408'] = array('name' => 'Clarinet', 'cost' => '299.50');

require_once('cart.php');

// Lấy hành động để xử lý
$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action === NULL) {
        $action = 'show_add_item';
    }
}

// Thực hiện hành động
switch($action) {
    case 'add':
        $product_key = filter_input(INPUT_POST, 'productkey');
        $item_qty = filter_input(INPUT_POST, 'itemqty');
        add_item($product_key, $item_qty);
        include('cart_view.php');
        break;
    case 'update':
        $new_qty_list = filter_input(INPUT_POST, 'newqty', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach($new_qty_list as $key => $qty) {
            if ($_SESSION['cart12'][$key]['qty'] != $qty) {
                update_item($key, $qty);
            }
        }
        include('cart_view.php');
        break;
    case 'show_cart':
        include('cart_view.php');
        break;
    case 'show_add_item':
        include('add_item_view.php');
        break;
    case 'empty_cart':
        unset($_SESSION['cart12']);
        include('cart_view.php');
        break;
    case 'end_session':
        $_SESSION = array(); // Xóa dữ liệu session
        session_destroy();   // Xóa session ID
        // Xóa cookie
        $name = session_name();                // Lấy tên của session cookie
        $expire = strtotime('-1 year');        // Tạo ngày hết hạn âm
        $params = session_get_cookie_params(); // Lấy các thông số của session
        $path = $params['path'];
        $domain = $params['domain'];
        $secure = $params['secure'];
        $httponly = $params['httponly'];
        setcookie($name, '', $expire, $path, $domain, $secure, $httponly);
        include('cart_view.php');
        break;
}
?>