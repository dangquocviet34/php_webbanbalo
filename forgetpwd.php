<?php
include "config.php";
include "header.php";

$errors = '';
$showChangePasswordForm = false;
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["btnxacnhan-email"])) {
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $role = mysqli_real_escape_string($conn, $_POST["role"]);

        $table = ($role === "admin") ? "admin" : "user";
        $query = "SELECT * FROM $table WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 1) {
            $showChangePasswordForm = true;
        } else {
            $errors = '<div id="errformlg">Email không tồn tại!</div>';
        }
    } elseif (isset($_POST["btnxacnhan-password"])) {
        $newpwd = mysqli_real_escape_string($conn, $_POST["newpwd"]);
        $confirmpwd = mysqli_real_escape_string($conn, $_POST["confirmpwd"]);
        $role = mysqli_real_escape_string($conn, $_POST["role"]);

        if (empty($newpwd) || empty($confirmpwd)) {
            $errors = '<div id="errformlg">Các trường không được để trống!</div>';
        } elseif ($newpwd !== $confirmpwd) {
            $errors = '<div id="errformlg">Mật khẩu xác nhận không khớp!</div>';
        } else {
            $hashedPwd =  md5($newpwd);
            $email = $_POST["email"];
            $table = ($role === "admin") ? "admin" : "user";

            $updateQuery = "UPDATE $table SET password = ? WHERE email = ?";
            $stmt = mysqli_prepare($conn, $updateQuery);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $email);
                $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    $successMessage = '<div id="errformlg">Mật khẩu đã được thay đổi thành công!</div>';
                } else {
                    $errors = '<div id="errformlg">Có lỗi khi thực hiện câu lệnh SQL: ' . mysqli_error($conn) . '</div>';
                }
                mysqli_stmt_close($stmt);
            } else {
                $errors = '<div id="errformlg">Có lỗi khi chuẩn bị câu lệnh SQL: ' . mysqli_error($conn) . '</div>';
            }
        }
    }
}
?>

<!DOCTYPE HTML>
<html lang="vi">
<head>
    <script>
        function showChangePasswordForm() {
            document.getElementById("emailForm").style.display = "none";
            document.getElementById("passwordForm").style.display = "block";
        }
    </script>
</head>
<body>
<div class="login-box">
    <div class="acctitle">
        <i class="fa fa-lock"></i>
        QUÊN MẬT KHẨU
    </div>
    <form method="post" class="signin" action="">
        <fieldset class="textbox" id="emailForm" <?php if ($showChangePasswordForm) echo 'style="display: none;"'; ?>>
            <label class="user_name">
                <div class='radico-container'>
                    <input type="radio" name="role" value="admin" id="admin" required>
                    <label for="admin">Admin</label>
                </div>
                <div class='radico-container'>
                    <input type="radio" name="role" value="user" id="user" required>
                    <label for="user">User</label>
                </div>
            </label>
            <label class="user_name">
                <span>Nhập email</span>
                <input type="text" name="email" id="email" value=""/>
            </label>
            <button class="submit button" type="submit" name="btnxacnhan-email">Xác nhận</button>
            <div class="dk-qmk">
                <a class="register" href="login.php">Quay lại</a>
            </div>
        </fieldset>
    </form>
</div>
<div class="login-box">
    <form method="post" class="signin" action="">
        <fieldset class="textbox" id="passwordForm" <?php if (!$showChangePasswordForm) echo 'style="display: none;"'; ?>>
            <label class="user_name">
                <span>Nhập mật khẩu mới</span>
                <input type="password" name="newpwd" id="newpwd" value=""/>
            </label>
            <label class="password">
                <span>Xác nhận mật khẩu</span>
                <input type="password" name="confirmpwd" id="confirmpwd" value="" />
            </label>
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            <input type="hidden" name="role" value="<?php echo $role; ?>">
            <button class="submit button" type="submit" name="btnxacnhan-password">Đổi mật khẩu</button>
        </fieldset>
    </form>
</div>
<?php
if (!empty($errors)) echo $errors;
if (!empty($successMessage)) echo $successMessage;
?>
</body>
</html>
