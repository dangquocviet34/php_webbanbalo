<!DOCTYPE HTML>
<html lang="vi">
<?php
ob_start();
include "config.php";
include "header.php";
// so san pham da add vao cart
$prd = 0;
if(!empty($_SESSION['cart'])) $prd = count($_SESSION['cart']);
?>
<body>
<div class="navigation">
</div>
<div class="re-gis">
    <h1 class="DKTT"> Đăng ký tài khoản</h1>
    <form action="register.php" method="post" name="formdk">
        <label>Họ và tên:</label><br/>
        <input type="text" name="ht" class="ht" required/> <br/>
        <label>Địa chỉ email:</label><br>
        <input type="email" name="email" class="em" required>
        <br>
        <label>Tên đăng nhập:</label><br>
        <input type="text" name="tendn" class="tdn" required/>
        <br/>
        <label>Mật khẩu:</label><br>
        <input type="password" name="pw" class="pw" required/>
        <br/>
        <label>Xác nhận mật khẩu:</label><br>
        <input type="password" name="xnpw" class="xnpw" required/>
        <br/>
        <label>Điện thoại:</label><br>
        <input type="tel" name="sdt" maxlength="11" class="sdt" required/>
        <br/>
        <label>Địa chỉ giao hàng:</label><br>
        <input type="text" name="dcgh" class="dcgh"/>
        <br/>
<!--        Giới tính:<br>
        <br>
        <input type="radio" name="gt" value="Nam" class="gt"/>Nam <input type="radio" name="gt" value="Nữ" class="gt"/>Nữ<br/>-->
        <input type="submit" name="sbmit" class="btn-reg" value="Đăng ký">
        <input type="reset" name="rs" class="btn-reset" value="Reset">
    </form></div><!--end re-gis-->
    <?php
	
    if (isset($_POST['sbmit'])) {
        $hoten = $_POST['ht'];
        $email = $_POST['email'];
        $tendn = $_POST['tendn'];
        $pw = md5($_POST['pw']);
        $xnpw = md5($_POST['xnpw']);
        $sdt = $_POST['sdt'];
        $dcgh = $_POST['dcgh'];
    
        // Kiểm tra điều kiện bắt buộc cho các trường không được để trống
        if (!$hoten || !$email || !$tendn || !$pw || !$xnpw || !$sdt || !$dcgh) {
            echo '<span id="errformdk">Vui lòng nhập thông tin đầy đủ!<a href="javascript: history.go(-1)">Trở lại</a></span>';
            exit;
        } else {
            // Kiểm tra xem tên đăng nhập đã tồn tại chưa
            $check_username_query = "SELECT * FROM user WHERE username = ?";
            $stmt = mysqli_prepare($conn, $check_username_query);
            mysqli_stmt_bind_param($stmt, 's', $tendn);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
    
            if (mysqli_num_rows($result) > 0) {
                echo "<span id=\"errformdk\">Tên đăng nhập này đã có. Vui lòng chọn tên đăng nhập khác. <a href='javascript: history.go(-1)'>Trở lại</a></span>";
                exit;
            } else {
                // Kiểm tra địa chỉ email có hợp lệ
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo '<span id="errformdk">Địa chỉ email nhập không đúng!<a href="javascript: history.go(-1)">Trở lại</a></span>';
                    exit;
                } else {
                    // Kiểm tra số điện thoại có là số nguyên
                    if (!is_numeric($sdt)) {
                        echo '<span id="errformdk">Số điện thoại nhập không đúng!<a href="javascript: history.go(-1)">Trở lại</a></span>';
                        exit;
                    } else {
                        // Kiểm tra mật khẩu và xác nhận mật khẩu trùng khớp
                        if ($pw == $xnpw) {
                            // Chèn dữ liệu vào cơ sở dữ liệu
                            $insert_query = "INSERT INTO user (fullname, email, phone, username, password, level, address) VALUES (?, ?, ?, ?, ?, 3, ?)";
                            $stmt = mysqli_prepare($conn, $insert_query);
                            mysqli_stmt_bind_param($stmt, 'ssssss', $hoten, $email, $sdt, $tendn, $pw, $dcgh);
                            mysqli_stmt_execute($stmt);
    
                            echo "<span id=\"errformdk\">Đăng ký thành công!</span>";
                            header('location: index.php');
                        } else {
                            echo '<span id="errformdk">Xác nhận mật khẩu không trùng khớp!</span>';
                        }
                    }
                }
            }
        }
    }
    ?>


<div class="clear10px"></div>
<?php include ("footer.php");
ob_end_flush();
?>
</body>
</html>
