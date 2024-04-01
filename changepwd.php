<!DOCTYPE html>
    <head>
    
    </head>
    <body>

        <?php
            include "apps/config.php";
            include "apps/libs/header.php";
            
            $errors = '';
            
            if (isset($_POST["btnxacnhan"])) {
                $newpwd = $_POST["newpwd"];
                $confirmpwd = $_POST["confirmpwd"];
            
                if (empty($newpwd) || empty($confirmpwd)) {
                    $errors = '<div id="errformlg" style="">
                                Các trường không được để trống!
                            </div>';
                } elseif ($newpwd !== $confirmpwd) {
                    $errors = '<div id="errformlg">
                                Mật khẩu xác nhận không khớp!
                            </div>';
                } else {
                    // Retrieve the user's email (You need to modify this query)
                    $email = "user@example.com"; // Replace with the actual email retrieval code
            
                    // Update the user's password in the database
                    $hashedPwd = password_hash($newpwd, PASSWORD_DEFAULT);
                    $updateQuery = "UPDATE user SET password = '$hashedPwd' WHERE email = '$email'";
                    mysqli_query($conn, $updateQuery);
            
                    // Display a success message or redirect to a login page
                    echo '<div id="errformlg">
                            Mật khẩu đã được thay đổi thành công!
                        </div>';
                }
            }
        ?>
        <!DOCTYPE HTML>
        <html lang="vi">
        <?php include "apps/libs/header.php"; ?>
        <body>
        
        <div class="login-box">
            <div class="acctitle">
                <i class="fa fa-lock"></i>
                   QUÊN MẬT KHẨU
            </div>
            <form method="post" class="signin" action="changepwd.php">
                <fieldset class="textbox">
                    <label class="user_name">
                        <span>Nhập mật khẩu mới</span>
                        <input type="text" name="newpwd" id="newpwd" value=""/>
                    </label>

                    <label class="password">
                        <span>Xác nhận mật khẩu</span>
                        <input type="password" name="confirmpwd" id="confirmpwd" value="" />
                    </label>
                    <button class="submit button" type="submit" name="btnxacnhan">Xác nhận</button>

                    <div class="dk-qmk">
                        <a class="register" href="login.php">Quay lại</a>
                    </div>
                </fieldset>
            </form>

        </div><!--end login-->
        <?php
        if(!empty($errors)) echo $errors;
        ?>

        <?php 
        include ("footer.php");

        ?>
    </body>
</html>