<?php
    include('functions.php');
    if ($_GET['action'] == 'loginSignup'){

        $error = "";

        if(!$_POST['email']){
            $error = 'Enter an Email address!';
        } else if(!$_POST['password']){
            $error = 'Enter Password!';
        } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid Email address!";
        }

        if($error != ""){
            echo $error;
            exit();            
        }

        if($_POST['loginActive'] == '0'){
            $query = "SELECT * FROM `users` WHERE email = '" . mysqli_real_escape_string($link, $_POST['email']) . "' LIMIT 1";
            $result = mysqli_query($link, $query);

            if (mysqli_num_rows($result) > 0) {
                $error = "The Email address is already registered, try using Log In!";
            } else{
                $query = "INSERT INTO `users` (name, email, password) VALUES('". mysqli_real_escape_string($link, $_POST['name']) . "', '" . mysqli_real_escape_string($link, $_POST['email']) . "', '" . mysqli_real_escape_string($link, $_POST['password']) . "')";
            }
            if(mysqli_query($link, $query)){
                $_SESSION['id'] = mysqli_insert_id($link);
                $query = "UPDATE users SET password='" . md5(md5($_SESSION['id']).$_POST['password']) . "' WHERE id='" . $_SESSION['id'] . "' LIMIT 1";
                mysqli_query($link, $query);
                echo '1';
                
            } else{
                echo ("Couldn't create user - Please try again!");
            }
            
        } else{
            $query = "SELECT * FROM `users` WHERE email = '" . mysqli_real_escape_string($link, $_POST['email']) . "' LIMIT 1";
            $result = mysqli_query($link, $query);

            $row = mysqli_fetch_assoc($result);

            if ($row['password'] == md5(md5($row['id']).$_POST['password'])){
                echo '1';
                $_SESSION['id'] = $row['id'];
            } else{
                $error = "Could not find this Email/ Password combination - Please try again!";
            }


        }
        if($error != ""){
            print_r($error);
            exit();
        }
        
    }

    if($_SESSION['id'] && $_GET['action'] == 'togglefollow'){

        $query = "SELECT * FROM isfollowing WHERE follower='" . mysqli_real_escape_string($link, $_SESSION['id']) . "' AND isfollowing='" . mysqli_real_escape_string($link, $_POST['userId']) . "' LIMIT 1";
        $result = mysqli_query($link, $query);

        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);

            mysqli_query($link, "DELETE FROM isfollowing WHERE id= '". mysqli_real_escape_string($link, $row['id'])."' LIMIT 1");
            echo '1';
        } else{

            mysqli_query($link, "INSERT INTO isfollowing (follower, isfollowing) VALUES('".mysqli_real_escape_string($link, $_SESSION['id']). "', '" . mysqli_real_escape_string($link, $_POST['userId']) . "')");
            echo "2";
        }
    }

    if($_GET['action'] == 'postTweet'){
        if(!$_POST['tweetContent']){
            echo "Your tweet is empty!";
        } else if(strlen($_POST['tweetContent']) > 1000){
            echo "Your tweet is lengthy, write in less than 1000 letters!";
        } else{
            mysqli_query($link, "INSERT INTO tweets (tweets, userid, datetime) VALUES('".mysqli_real_escape_string($link, $_POST['tweetContent']). "', '" . mysqli_real_escape_string($link, $_SESSION['id']) . "', NOW())");
            echo "1";
        }
    }
?>