<!DOCTYPE HTML>
<html lang="vi">
<?php
require("config.php");
require("header.php");
?>
<head>
    <style>
        /* Your existing CSS styles here */

        /* Add a class for the cart detail container */
        .cart-details {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .cart-details .top_cart,
        .cart-details .bottom_cart {
            display: flex;
            justify-content: space-between; /* Add this property to create space between elements */
            align-items: center;
            margin: 10px 0;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .cart-details .cartImg {
            max-width: 100px;
            max-height: 100px;
            margin-right: 10px;
        }

        .cart-details .Cart_title_pro {
            font-weight: bold;
        }

        .cart-details .delete_Cart {
            margin-top: 10px;
        }

        .cart-details .sum_money {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }

        .cart-details .sum_sp {
            color: #007bff;
        }

        .cart-details .update_cart {
            text-align: right;
            margin-top: 10px;
        }

        .cart-details .cap_nhat_cart,
        .cart-details .dat_hangsr {
            padding: 6px 12px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        .cart-details .dat_hangsr {
            background-color: #007bff;
            color: #fff;
        }

        .cart-details .dat_hangsr:hover {
            background-color: #0056b3;
        }

        .cart-details .cap_nhat_cart {
            background-color: #ccc;
            color: #333;
        }

        .cart-details .cap_nhat_cart:hover {
            background-color: #b3b3b3;
        }
        .ttkh div {
        text-align: center; /* Căn chỉnh nội dung bên trong div vào giữa */
        position: absolute; /* Đặt vị trí tương đối hoặc tuyệt đối */
        top: 50%; /* Đặt phần trên của div ở giữa trang */
        left: 50%; /* Đặt phần trái của div ở giữa trang */
        transform: translate(-50%, -50%); /* Di chuyển div để căn chỉnh giữa trang */
        }

    </style>
</head>
<body>
<div class="navigation"></div>
<?php
if ($_SESSION['cart'] != NULL) {
    echo '
    <form method="post" action="orders.php">
        <div class="cart-details">
            <p class="cart_info">Thông tin Giao hàng';

    if (isset($_SESSION['username'])) {
        $khachhang = mysqli_query($conn, "SELECT * FROM user WHERE username ='" . $_SESSION['username'] . "'");
        $items = mysqli_fetch_array($khachhang);
        echo '<div class="ttkh"> 
            <span class="ttkh">THÔNG TIN KHÁCH HÀNG:</span>
            <br/>Họ và tên: ' . $items['fullname'] . '
            <br/>Điện thoại:' . $items['phone'] . '
            <br/>Địa chỉ giao hàng: ' . $items['address'] . '
        </div><!--end ttkh-->
        <span class="ttkh">THÔNG TIN CHI TIẾT CÁC SẢN PHẨM TRONG ĐƠN HÀNG:</span>
        <div class="clear10px"></div>
        <div class="cart-details">
            <ul class="top_cart">
                <li class="sp">SẢN PHẨM </li>
                <li class="dg">ĐƠN GIÁ</li>
                <li class="sl">SỐ LƯỢNG</li>
                <li class="tt">THÀNH TIỀN</li>
            </ul>';
        $sum_all = 0;
        $sum_sp = 0;
        if ($_SESSION['cart'] != NULL) {
            foreach ($_SESSION['cart'] as $id => $prd) {
                $arr_id[] = $id;
            }
            $str_id = implode(',', $arr_id);
            $item_query = "SELECT * FROM  sanpham WHERE id_sanpham IN ($str_id) ORDER BY id_sanpham ASC";
            $item_result = mysqli_query($conn, $item_query) or die('Cannot select table!');
            while ($rows = mysqli_fetch_array($item_result)) {
                echo '<ul class="bottom_cart">
                    <li class="sp">
                        <a><img src="' . $base . '/images/' . $rows['image_sp'] . '" class="cartImg"></a>
                        <a class="Cart_title_pro">' . $rows['tensp'] . '</a>
                        <div class="delete_Cart"><a href="' . $base . '/del-product.php?id=' . $rows['id_sanpham'] . '">Xóa</a></div>
                    </li>
                    <li class="dg">' . number_format($rows['price']) . ' VNĐ</li>
                    <li class="sl"><input type="text" name ="num[' . $rows['id_sanpham'] . ']" value="' . $_SESSION['cart'][$rows['id_sanpham']] . '" size="3" class="capnhatCartTxt"/></li>
                    <li class="tt">' . number_format($rows['price'] * $_SESSION['cart'][$rows['id_sanpham']]) . ' VNĐ</li>
                </ul>';
                $sum_all += $rows['price'] * $_SESSION['cart'][$rows['id_sanpham']];
                $sum_sp += $_SESSION['cart'][$rows['id_sanpham']];
            }
        }
        echo '</div><!--end cart-details-->';
        echo '
        <p class="update_cart">
            <input type="submit" name="update_cart" value="Cập nhật giỏ hàng" class="cap_nhat_cart"/>
            <input type="submit" name="btDathang" value="ĐẶT HÀNG" class="dat_hangsr"/>
        </p><!---end update-cart--->
        <p class="sum_money">Tổng sản phẩm:<strong class="sum_sp">' . $sum_sp . '</strong><br/>Tổng tiền:<strong class="sum_sp">' . number_format($sum_all) . ' VNĐ</strong></p>
    </form>
    </div><!--end cart-details-->';
    } else {
        echo '<a href="' . $base . '/login.php" class="">&nbsp;</a>
        <div class="re-gis">
            <form action="orders.php" method="post" id="dathang">
                <label>Họ và tên:</label><br/>
                <input type="text" name="fn" class="ht" maxlength="50" required/> <br/>
                <label>Địa chỉ email:</label><br>
                <input type="email" name="em" class="em" maxlength="50" required>
                <br>
                <label>Điện thoại:</label><br>
                <input type="tel" name="tphone" maxlength="11" class="sdt" required/>
                <br/>
                <label>Địa chỉ giao hàng:</label><br>
                <input type="text" name="ar" class="dcgh" required/>
                <br/>
                <label>Ghi chú:</label><br>
                <textarea placeholder="Ghi chú đơn hàng" name="notetext" rows="3"></textarea>
                <br/>
                <input type="submit" name="dathang_two" class="btn-reg" value="ĐẶT HÀNG">
                <input type="reset" name "rs" class="btn-reset" value="Reset">
            </form>
        </div><!--end re-gis-->';
    }
} else {
    echo '<div class="clear10px"></div>
    <span id="gohome" style="padding: 27px;display: block;text-align: center;font-size: 16px;line-height: 30px;">Hiện tại bạn chưa có sản phẩm nào! <br>
    <a href="' . $base . '" style="color: blue">Trở lại trang chủ</a></span>';
}
?>
<?php include("footer.php"); ?>
<style>
    form input.ht, form input.em, form input.tdn, form input.pw, form input.xnpw, form input.sdt, form input.dcgh, textarea {
        width: 530px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-left: 310px;
    }
</style>
</body>