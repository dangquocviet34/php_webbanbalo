<!DOCTYPE HTML>
<html>
<?php
require("config.php");
require("header.php");
?>

<body>
    <section class="content_ldd">
        <!-- Phan ben trai -->
        <aside class="filter" id="filter_cate">
            <div class="filter_v">
                <div class="general">
                    <ul class="menu_left">
                        <?php
                        $menu_left_res = mysqli_query($conn, "select * from sub_menu limit 0,3");
                        echo "<li><a href='#' class='tittlea'>TÚI XÁCH</a>
									<ul class='menu_in_left'>";
                        while ($menu_left_items = mysqli_fetch_array($menu_left_res)) {
                            echo "<li><a href='sanpham.php?id_menu=" . $menu_left_items['id_sub'] . "'>" . $menu_left_items['name_sub'] . " </a></li>";
                        }
                        echo "
									</ul>
						</li>";
                        ?>
                        <?php
                        $menu_left_res = mysqli_query($conn, "select * from sub_menu limit 3,2");
                        echo "<li><a href='#' class='tittlea'>VÍ - BÓP</a>
									<ul class='menu_in_left'>";
                        while ($menu_left_items = mysqli_fetch_array($menu_left_res)) {
                            echo "<li><a href='sanpham.php?id_menu=" . $menu_left_items['id_sub'] . "'>" . $menu_left_items['name_sub'] . " </a></li>";
                        }
                        echo "
									</ul>
						</li>";
                        ?>
                        <?php
                        echo "<a href='#' class='tittlea'>BA LÔ</a>
                        <ul class='menu_in_left'>";
                        ?>
                        
                    </ul>
                </div><!--end general-->
            </div><!--end filter_v-->
        </aside><!--end ben trai -->

        <!-- Phan ben Phai -->
        <aside class="product_l">
            <div class="product_boder">
                <?php

                $id_menu = $_GET["id_menu"];
                settype($id_menu, "int");
                // tong so records
                $result = mysqli_query($conn, "select count(id_sanpham) as total from sanpham where id_sub ={$id_menu} or id_catalog = {$id_menu}");
                $row = mysqli_fetch_assoc($result);
                $total_records = $row['total']; //gán tổng số record là total, đếm bằng id sản phẩm
                // tim limit va current
                $current_page = isset($_GET['page']) ? $_GET['page'] : 1; //kiểm tra $_GET['page'] khi bấm trang kế có tồn tại không
                $litmit = 8; // tổng số record trên 1 trang
                $total_page = ceil($total_records / $litmit); // tổng số page khi mỗi page hiển thị bao nhiêu sp
                if ($current_page > $total_page) {
                    $current_page = $total_page;
                } else if ($current_page < 1) {
                    $current_page = 1;
                }
                $start = ($current_page - 1) * $litmit;
                $result = mysqli_query($conn, "SELECT * FROM sanpham where id_sub ={$id_menu} or id_catalog = {$id_menu} LIMIT {$start}, {$litmit}");
                ?>

                <?php
                if (!empty($total_records)) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='product_item'>";
                        echo "
							<a href='chitiet.php?id=" . $row['id_sanpham'] . "' class='images'>
							<img alt='" . $row['tensp'] . "' src='images/" . $row['image_sp'] . "'>
							</a>
							<h2 style='margin-top:0;margin-bottom:0;'>
							<a title='" . $row['tensp'] . "' href='chitiet.php?id=" . $row['id_sanpham'] . "'>" . $row['tensp'] . "</a>
							</h2>
							<div class='price'>" . number_format($row['price']) . " VNĐ</div><!--end price-->
							<div class='ratings'>
								<div class='rating-box'>
								</div><!--end ratingbox-->
							</div><!--end ratings-->
							<a href='controllers/add-cart.php?id=" . $row['id_sanpham'] . "'><div class='add_cart''><i></i>THÊM VÀO GIỎ </div></a>
							";
                        echo "</div><!--end product_items-->";
                    }
                } else {
                    echo "Sản phẩm hiện tại chưa có";
                }
                ?>

            </div><!--end product_boder-->
            <div class="phan_trang" style="margin-left: calc(936px/2);">
                <?php
                if ($current_page > 1 && $total_page > 1) {
                    echo "<a href='sanpham.php?id_menu=" . $id_menu . "&page=" . ($current_page - 1) . "'>
								<b class='prev'></b>
							</a>";
                }
                echo "<ul class='ul_phan_page'>";
                for ($i = 1; $i <= $total_page; $i++) {
                    if ($i == $current_page) {
                        echo "<li><span class='color_current'>" . $i . "</span></li>";
                    } else
                        echo '<li><a href="sanpham.php?id_menu=' . $id_menu . '&page=' . $i . '">' . $i . '</a></li>';
                }
                echo "</ul>";
                if ($current_page < $total_page && $total_page > 1) {
                    echo "<a href='sanpham.php?id_menu=" . $id_menu . "&page=" . ($current_page + 1) . "'>
							<b class='next'></b>
						</a>";
                }

                ?>
            </div><!--end phan_page-->
        </aside><!--end ben phai-->
    </section><!--end content ld-->
    <?php require("footer.php"); ?>
</body>

</html>
<?php
?>