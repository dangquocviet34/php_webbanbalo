<!DOCTYPE HTML>
<html lang="vi">
<?php
$prd = 0;
$pvc = 5000 * rand(3, 15);
if (!empty($_SESSION['cart']))
    $prd = count($_SESSION['cart']);


?>

<body></body>
<?php
include("config.php");
include("header.php");
?>
<div class="navigation"></div>
<form method="post">
    <div class="main-shopping">
        <p class="cart_info">
            <?php 
            if ($_SESSION['cart'] != NULL) {
                echo "Thông tin chi tiết giỏ hàng!";
                echo '</p><div class="cart_order_1">
                        <ul class="table_cart">
                            <li class="ch_cart">CHỌN</li>
                            <li class="sp_cart">SẢN PHẨM </li>
                            <li class="dg_cart">ĐƠN GIÁ</li>
                            <li class="sl_cart">SỐ LƯỢNG</li>
                            <li class="tt_cart">THÀNH TIỀN</li>
                        </ul>';
            } else {
                echo "Hiện tại bạn chưa có sản phẩm nào!";
                echo '</p></div></form>';
            } ?>

            <?php
            $sum_all = 0;
            $sum_sp = 0;
            if ($_SESSION['cart'] != NULL) {
                foreach ($_SESSION['cart'] as $id => $prd) {
                    $arr_id[] = $id;
                }
                $str_id = implode(',', $arr_id);
                $item_query = "SELECT * FROM  sanpham WHERE id_sanpham IN ($str_id) ORDER BY id_sanpham ASC";
                $item_result = mysqli_query($conn, $item_query) or die('Cannot select table!');
                $index = 0;
                while ($rows = mysqli_fetch_array($item_result)) {
                    ?>
                    <!--SHOW CART-->

                <ul class="bottom_cart">
                    <?php
                    echo ' <li class="ch_cart"> <input type="checkbox" id="' . $index . '" name="' . $index . '" value="' . $index . '"> </li>';
                    $index += 1;
                    ?>
                    <li class="sp_cart">
                        <img src="images/<?php echo $rows['image_sp']; ?>" class="cartImg">
                        <b class="Cart_title_pro">
                            <?php echo $rows['tensp']; ?>
                        </b>
                        <div class="delete_Cart" style="text-align: center;font-size: 15px;"><a
                                href="<?php echo $base ?>controllers/del-product.php?id=<?php echo $rows['id_sanpham']; ?>"
                                class="del_sp"><i class="fa fa-trash" aria-hidden="true"></i></a></div>

                    </li>
                    <li class="dg_cart">
                        <div>
                            <?php echo number_format($rows['price']); ?> VNĐ
                        </div>
                    </li>
                    <li class="sl_cart"><input type="text" name="num[<?php echo $rows['id_sanpham']; ?>]"
                            value="<?php echo $_SESSION['cart'][$rows['id_sanpham']]; ?>" size="3" class="capnhatCartTxt" />
                    </li>
                    <li class="tt_cart">
                        <div>
                            <?php echo number_format($rows['price'] * $_SESSION['cart'][$rows['id_sanpham']]); ?>
                            VNĐ
                        </div>
                    </li>
                </ul>
                <?php
                $sum_sp += $_SESSION['cart'][$rows['id_sanpham']];
                $sum_all += $rows['price'] * $_SESSION['cart'][$rows['id_sanpham']];
                }
                echo '
                    </div><!--end cart_oder-->
                    <div class="bottom_button">
                    <p class="update_cart">
                        <input type="submit" name="update_cart" value="Cập nhật giỏ hàng" class="cap_nhat_cart"/>
                    </p>
                    </div>
                    <!--end update-cart--->
                    <hr />';

                if (isset($_POST['num'])) {
                    $_SESSION['cart'] = $_POST['num'];
                    echo "<script>location.href='cart.php';</script>";

                }
                $tam_tinh = $sum_all;
                $gtgt = $sum_all * 0.1;
                $sum_all = $sum_all + $pvc + $gtgt;
                echo '
                <div class="bottom-summary">
                <table>
                    <tr>
                        <td class="sum_name">Tổng sản phẩm:</td>
                        <td class="sum_value">' . $sum_sp . '</td>
                    </tr>
                    <tr>
                        <td class="sum_name">Tạm tính:</td>
                        <td class="sum_value">' . number_format($tam_tinh) . 'VNĐ</td>
                    </tr>
                    <tr>
                        <td class="sum_name">Vận chuyển:</td>
                        <td class="sum_value">' . number_format($pvc) . 'VNĐ</td>
                    </tr>
                    <tr>
                        <td class="sum_name">GTGT (10%):</td>
                        <td class="sum_value">' . number_format($gtgt) . 'VNĐ</td>
                    </tr>
                    <tr>
                        <td class="sum_name">Tổng tiền:</td>
                        <td class="sum_value">' . number_format($sum_all) . 'VNĐ</td>
                    </tr>
                </table>
                <a href="' . $base . 'checkout.php" class="dat_hang" style="display:block;">THANH TOÁN</a>
                <a href="' . $base . '" class="back_page"> Tiếp tục mua hàng</a>
                <a href="controllers/del-product.php?id=0" class="delete_all">Xóa giỏ hàng</a>
                </div>
                <div class="clear10px"></div> ';
            } ?>
    </div>
</form>
</body>
<?php include("footer.php"); ?>

</html>