<?php   
    session_start();
    if($_SESSION && $_SESSION['id']){
        header('Location: index.php');
    } else{
    $link = mysqli_connect('shareddb-v.hosting.stackcp.net', 'twitter-31343717ca', 'uu5k3mxz2r', 'twitter-31343717ca');
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $otp = "";
    function generate_string($input, $strength = 8) {
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
     
        return $random_string;
    }
    $errorMess = "";
    if($_POST && $_POST['femail'] && !$_POST['otp']){
        $emailTo = $_POST['femail'];
        $subject = "Twitter | OTP Generator";
        $otp =  generate_string("0123456789", 4);
        $_SESSION['otp'] = $otp;
        $_SESSION['femail'] = $emailTo;
        $body = 'We have received a request from your end to send the OTP for the Twitter account.

Your OTP: "' . $otp . '"';
        $headers = "From: harshsainiprojects-twitter-com@stackstaging.com";

        $query = "SELECT * FROM users WHERE email = '" . mysqli_real_escape_string($link, $emailTo) . "'";
        if($result = mysqli_query($link, $query)){
            if($row = mysqli_fetch_array($result)){
                if($row){
                    if (mail($emailTo, $subject, $body, $headers)){
                        $errorMess = '<div class="alert alert-success" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;"><p>OTP sent on your registered Email!</p></div>';
                    } else{
                        $errorMess = '<div class="alert alert-danger" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;"><p>OTP could not sent.</p></div>';
                    }
                }                 
            } else{
                $errorMess = '<div class="alert alert-danger" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;"><p>Email doesn\'t exist in our records!</p></div>';
            }
            
        } 
    }

    if ($_POST && $_POST['otp'] && $_POST['newPassword'] && !$_POST['femail']){
        $userotp = $_POST['otp'];
        if($userotp == $_SESSION['otp']){
            $newpassword = $_POST['newPassword'];
            $que = "SELECT * FROM `users` where email='" . mysqli_real_escape_string($link, $_SESSION['femail']) . "'";
            $res = mysqli_query($link, $que);
            $rw = mysqli_fetch_assoc($res);
            $query = "UPDATE users SET password = '" . md5(md5($rw['id']) . $newpassword) .  "' WHERE email = '" . mysqli_real_escape_string($link, $_SESSION['femail']) . "'";
            if (mysqli_query($link, $query)){
                    $errorMess = '<div class="alert alert-success" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;"><p>OTP Verified</p><p>New Password has been updated!</p></div>';
            }else{
                $errorMess = '<div class="alert alert-warning" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;"><p>Could not update password, contact Admin</p></div>';
            }
        } else{
            $errorMess = '<div class="alert alert-danger" role="alert" style="width: 70%;margin: auto; margin-bottom: 20px;"><p>Incorrect OTP</p><p><a href="forgot.php">Reload the page by clicking this link!</a></p></div>';
        }
        $_SESSION['otp'] = '';
        $_SESSION['femail'] = '';
        $_SESSION['page'] = '1';
    }

    unset($_POST);
    unset($_REQUEST);
}
?>

<html>
    <head>
        <title>Fogot Password</title>
        <style type="text/css">
            *{
                font-family: Georgia, 'Times New Roman', Times, serif
            }
            body{
                background-image: url('bgg.jpg');
                /* Full height */
                height: 100%;

                /* Center and scale the image nicely */
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
            .container{
                text-align: center;
                margin-top: 10%;
                padding-top: 8px;
                padding-bottom: 8px;
                font-size: 120%;
                border-radius: 10px;
            }
            #container{
                width: 60%;
            }
            #form{
                width: 70%;
                margin: 0 auto;
            }
        </style>        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    </head>
    <body>
        <div class="container" id="container">
            <h2>Forgot Password</h2>
            <p>Don't panic, We are here to solve your problem</p>
            <hr style="color: black; width:80%">
            <div><? echo ($errorMess); ?></div>
            <p>Enter your registered Email</p>
            <form id="form" method="post">
                <div class="form-group">
                  <input type="email" name="femail" class="form-control" id="femail" aria-describedby="emailHelp" placeholder="Enter Your Email">
                </div>
                <button type="submit" class="btn btn-primary" id="sendotpbutton">Send OTP</button>
            </form>
            <br><br>
            <p>Enter OTP</p>
            <form id="form" method="post">
                <div class="form-group">
                  <input type="password" name="otp" class="form-control" id="otp" placeholder="Enter OTP">
                  <br>
                  <p>Enter new Password</p>
                  <input type="password" name="newPassword" class="form-control" id="newPassword" placeholder="Enter new Password">
                </div>
                <button type="submit" class="btn btn-success" id="verifybutton">Verify OTP and set new Password</button>
            </form>
        <br>
        <a href="index.php" style="margin-top:25px">Home Page</a>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </body>
</html>