        <br><br><br>
        <footer class="footer mt-auto py-3 fixed-bottom">
        <div class="container">
            <span class="text-muted">&copy; Harsh Saini</span>
        </div>
        </footer>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        
        <!-- Modal -->
        <div class="modal fade" id="loginSignup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginTitle">Log In</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div class="alert alert-danger" id="loginAlert" style="display:none;"></div>
            <form method="post">
                <input type="hidden" id="activeLogin" val='1'>
                <div class="form-group" id="signupName">
                    <label for="name">Name</label>
                    <input type="name" class="form-control" id="name" placeholder="Name">
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Email Address">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Password">
                </div>
            </form>
            <center><button type="button" class="btn btn-success" id="loginButton">Log In</button></center>
            <br>
            <center id="forgotPassword"><a href="forgot.php">Forgot Password</a></center>
            </div>
            <div class="modal-footer">
                <center><a href="#" id="toggleLogin">Sign Up</a></center>
            </div>
            </div>
        </div>
        </div>

        <script>
            $('#activeLogin').val('1');
            $('#signupName').hide();
            $('#toggleLogin').click(function(){
                if($('#activeLogin').val() == '1') {
                    $('#signupName').show();
                    $('#forgotPassword').hide();
                    $('#activeLogin').val('0');
                    $('#loginTitle').html('Sign Up');
                    $('#toggleLogin').html('Log In');
                    $('#loginButton').html('Sign Up');
                } else{
                    $('#signupName').hide();
                    $('#forgotPassword').show();
                    $('#activeLogin').val('1');
                    $('#loginTitle').html('Log In');
                    $('#toggleLogin').html('Sign Up');
                    $('#loginButton').html('Log In');
                }
            });

            $('#loginButton').click(function(){
                $.ajax({
                    type: "POST",
                    url: "actions.php?action=loginSignup",
                    data: "email=" + $('#email').val() + "&name=" + $('#name').val() + "&password=" + $('#password').val() + "&loginActive=" + $('#activeLogin').val(),
                    success: function(result){
                        if (result == '1'){
                            window.location.assign('http://harshsainiprojects-com.stackstaging.com/twitter/index.php');
                        } else{
                            $('#loginAlert').html(result).show();
                        }
                    },
                    error: function (request, status, error) {
                        alert(request.responseText);
                    }
                });
            });

            $('.toggleFollow').on('click', function(){
                var id = $(this).attr('data-userId');
                $.ajax({
                    type: "POST",
                    url: "actions.php?action=togglefollow",
                    data: "userId=" + id,
                    success: function(result){
                        if(result =='1'){
                            $("a[data-userId='" + id + "']").html('Follow');
                        } else if(result =='2'){
                            $("a[data-userId='" + id + "']").html('Unfollow');
                        } else{
                            alert('Signup first!!');
                        }
                    },
                    error: function (request, status, error) {
                        alert(request.responseText);
                    }
                });
            });

            $('#postTweetButton').click(function(){
                $.ajax({
                    type: "POST",
                    url: "actions.php?action=postTweet",
                    data: "tweetContent=" + $('#tweetContent').val(),
                    success: function(result){
                        if(result == 1){
                            $('#tweetSuccess').show();
                            $('#tweetFail').hide();
                        } else if(result != ""){
                            $('#tweetFail').html(result).show();
                            $('#tweetSuccess').hide();
                        }
                    },
                    error: function (request, status, error) {
                        alert(request.responseText);
                    }
                });
            });
        </script>
    </body>
</html>