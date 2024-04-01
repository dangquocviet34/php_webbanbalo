<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .radio-container{
        }
        
    </style>
</head>
<?php
include "config.php";
// Khởi tạo phiên làm việc

$errors = '';

if (isset($_POST["btnsubmit"])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $errors = '<div id="errformlg">Các trường không được để trống!</div>';
    } else {
        $username = $_POST['username'];
        $password = md5($_POST['password']); // Cần mã hóa mật khẩu (nếu bạn lưu mật khẩu đã mã hóa trong cơ sở dữ liệu)

        // Kiểm tra xem radio "role" có giá trị là "admin" không
        if (isset($_POST['role']) && $_POST['role'] === 'admin') {
            $table = "admin";
            $redirect_url = "admin/index.php";
        } else {
            $table = "user";
            $redirect_url = "index.php";
        }

        $sql = "SELECT * FROM $table WHERE username = ? and password = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            $errors = '<div id="errformlg">Tên đăng nhập hoặc mật khẩu không đúng!</div>';
        } else {
            if ($table = "user") {
                $_SESSION['username'] = $username;
                header('Location: ' . $redirect_url);
            } else {
                header('Location: ' . $redirect_url);
            }
            exit();
        }
    }
}
?>

<!DOCTYPE HTML>
<html lang="vi">
<?php include "header.php"; ?>

<body>
    <!--ĐĂNG NHẬP-->
    <div class="login-box">
        <div class="acctitle">
            <i class="fa fa-lock"></i>
            Đăng nhập
        </div>
        <form method="post" class="signin" action="">
            <fieldset class="textbox">
                <label class="user_name">
                    <div class='radio-container'>
                        <input type="radio" name="role" value="admin" id="admin" required>
                        <label for="admin">Admin</label>
                    </div>
                    <div class='radio-container'>
                        <input type="radio" name="role" value="user" id="user" required><label for="user">User</label>
                    </div>
                </label>
                <label class="user_name">
                    <span>Tên tài khoản</span>
                    <input type="text" name="username" id="user_name" value="" />
                </label>

                <label class="password">
                    <span>Mật khẩu</span>
                    <input type="password" name="password" id="password" value="" />
                </label>
                <button class="submit button" type="submit" name="btnsubmit">Đăng nhập</button>
                <div class="dk-qmk">
                    <a class="forgot" href="forgetpwd.php">Quên mật khẩu?</a><a href="register.php"
                        class="register">Đăng ký</a>
                </div>
            </fieldset>
        </form>

    </div><!--end login-->
    <?php
    if (!empty($errors))
        echo $errors;
    ?>
    <!--end login-->
    <!--END ĐĂNG NHẬP-->
    <?php include("footer.php");

    ?>
</body>

</html>