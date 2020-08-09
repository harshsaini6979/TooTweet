<?php
    session_start();
    $link = mysqli_connect('shareddb-v.hosting.stackcp.net', 'twitter-31343717ca', 'uu5k3mxz2r', 'twitter-31343717ca');

    if(mysqli_connect_errno()){
        print_r(mysqli_connect_error());
        exit();
    }

    function time_since($since) {
        $chunks = array(
            array(60 * 60 * 24 * 365 , 'year'),
            array(60 * 60 * 24 * 30 , 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24 , 'day'),
            array(60 * 60 , 'hour'),
            array(60 , 'min'),
            array(1 , '')
        );
    
        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }
    
        $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
        return $print;
    }

    if($_GET['function'] == 'logout'){
        session_unset();
    }
    
    function displayTweets($type){
        global $link;
        $whereClause = "";
        if($type == 'public'){
            $whereClause = "";
        } else if($type == 'isFollowing'){
            $query = "SELECT * FROM isfollowing WHERE follower='" . mysqli_real_escape_string($link, $_SESSION['id']) . "'";
            $result = mysqli_query($link, $query);

            $whereClause = "";

            while($row = mysqli_fetch_assoc($result)){
                if($whereClause == "") {$whereClause = "WHERE ";}
                else {$whereClause .= " OR";}
                $whereClause .= " userid='" . $row['isfollowing'] . "'";
            }

            if ($whereClause == ""){
                $whereClause = "WHERE follower=". $_SESSION['id'] ."'";
            }
        } else if($type == "yourtweets"){
            $whereClause = "WHERE userid='" . mysqli_real_escape_string($link, $_SESSION['id']) . "'";
        } else if($type == "search"){
            echo "<p>Showing results for <strong>\"" . mysqli_real_escape_string($link, $_GET['q']) . "\"</strong>:</p>";
            $whereClause = "WHERE tweets LIKE'%" . mysqli_real_escape_string($link, $_GET['q']) . "%'";
        } else if(is_numeric($type)){
            $whereClause = "WHERE userid='" . mysqli_real_escape_string($link, $type) . "'";
            $userQuery = "SELECT * FROM users WHERE id='" . mysqli_real_escape_string($link, $type) . "' LIMIT 1";
            $userQueryResult = mysqli_query($link, $userQuery);
            $user = mysqli_fetch_assoc($userQueryResult);

            echo "<h2>" . mysqli_real_escape_string($link, $user['name'])."'s Tweets:</h2>";
        }

        $query = "SELECT * FROM tweets " . $whereClause . " ORDER BY `datetime` DESC";
        $result = mysqli_query($link, $query);
        if (mysqli_num_rows($result) == 0){
            echo "There is no tweets to display!!";
        } else{
            while($row = mysqli_fetch_assoc($result)){
                $userQuery = "SELECT * FROM users WHERE id='" . mysqli_real_escape_string($link, $row['userid']) . "' LIMIT 1";
                $userQueryResult = mysqli_query($link, $userQuery);
                $user = mysqli_fetch_assoc($userQueryResult);
                echo "<div id='tweet' style='border: 1px solid grey;border-radius: 5px;padding: 5px;margin: 5px;background-color:white;'><strong><a href='?page=publicprofiles&userid=".$user[id]."'>" . $user['name'] . "</a></strong><span style='color:lightgrey;float:right;'>". time_since(time() - strtotime($row['datetime'])) ." ago</span><p><span style='color:grey;'>". $user['email'] . " </span></p>";
                echo "<p>". $row['tweets'] ."</p>";
                if($_SESSION['id'] == $row['userid']){
                    echo "<p style='color:grey;'>(It's you)</p></div>";
                } else{
                    echo "<p><a class='toggleFollow' href='#' data-userId='".$row['userid']."'>";

                    $ifquery = "SELECT * FROM isfollowing WHERE follower='" . mysqli_real_escape_string($link, $_SESSION['id']) . "' AND isfollowing='" . mysqli_real_escape_string($link, $row['userid']) . "' LIMIT 1";
                    $ifresult = mysqli_query($link, $ifquery);

                    if(mysqli_num_rows($ifresult) > 0){
                        echo 'Unfollow';
                    } else{
                        echo "Follow";
                    }

                    echo "</a></p></div>";
                }
            }
        }
    }

    function displaySearch(){
        echo '<form class="form-inline">
        <div class="form-group">
        <input type="hidden" name="page" value="search">
        <input type="text" class="form-control mb-2 mr-sm-2" id="search" name="q" placeholder="Search" style="width:86%">     
        </div> 
        <button type="submit" class="btn btn-primary mb-2">Search Tweets</button>
      </form>';

    }

    function displayTweetBox(){
        global $link;
        if($_SESSION['id'] > 0){
            $query = "SELECT * FROM users WHERE id='" . mysqli_real_escape_string($link, $_SESSION['id']) ."'";
            $result = mysqli_query($link, $query);
            $row = mysqli_fetch_assoc($result);
            echo "<p>Hi     <span style='font-size:200%'>" . $row['name'] . "</span><br><span style='color:grey'>". $row['email'] . "</p>";
            echo '<div id="tweetSuccess" class="alert alert-success" style="display:none">Your Tweet was posted successfully.</div>
            <div id="tweetFail" class="alert alert-danger" style="display:none"></div>
            <div class="form">
            <div class="form-group">
            <textarea class="form-control mb-2 mr-sm-2" id="tweetContent" style="height:200px;width:100%;overflow:hidden;"></textarea>     
            </div> 
            <button class="btn btn-primary mb-2" id="postTweetButton">Post Tweet</button>
        </div>';
        }
    }

    function displayUsers(){
        global $link;

        $query = "SELECT * FROM users ORDER BY name";
        $result = mysqli_query($link, $query);
        while($row = mysqli_fetch_assoc($result)){
            echo "<div style='background-color:white;border:1px solid grey;padding:5px;border-radius:5px;margin-top: 5px;margin-bottom:5px;'><p><a href='?page=publicprofiles&userid=".$row['id']."'>". $row['name']."</a><br><span style='color:grey;'>".$row['email']."</span></p></div>";
        }
    }
?>
