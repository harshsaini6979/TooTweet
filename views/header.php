<html>
    <head>
        <title>Twitter</title>
        <link rel="stylesheet" type="text/css" href="http://harshsainiprojects-com.stackstaging.com/twitter/styles.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <head>
    <body class="d-flex flex-column h-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand btn outline-dark" href="http://harshsainiprojects-com.stackstaging.com/twitter/index.php">TWITTER</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="?page=timeline">Your Timeline</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?page=yourtweets">Your Tweets</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?page=publicprofiles">Public Profiles</a>
            </li>
            </ul>
            <div class="form-inline my-2 my-lg-0"><?php if($_SESSION['id']){ ?>
            <a class="btn btn-outline-danger my-2 my-sm-0" href="?function=logout">Logout</a>
            <?php } else{ ?>
            <button class="btn btn-outline-success my-2 my-sm-0" data-toggle="modal" data-target="#loginSignup">Log In / Sign Up</button>
            <?php } ?>
</div>
        </div>
        </nav>
        <br><br><br>
