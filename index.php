<?php require_once 'common.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>index</title>

    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">二手车交易市场</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <form class="navbar-form navbar-right">
                    <?php if($user != null):?>
                    <a class="text-center">
                        <?php
                            echo "User_ID: ".$user["user_id"];
                            echo " User_Age: ".$user["age"];
                        ?>
                    </a>
                    <a type="button" class="btn btn-primary" href="entry.php?type=logout">
                        Logout
                    </a>
                    <?php else:?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" id="loginBtn">
                        Login
                    </button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" id="registerBtn">
                        Register
                    </button>
                    <?php endif;?>
                </form>
            </div>
        </div>
    </nav>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Register</h4>
                </div>
                <div class="modal-body">
                    <div class="control-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">StudentID</span>
                            <input type="text" class="form-control" placeholder="Ten Number" aria-describedby="basic-addon1" id="studentID">
                        </div>
                        <div id="camera-content">
                            <video id="video" width="320" height="320" autoplay></video>
                            <button type="button" class="btn btn-primary" id="camera">拍照</button>
                            <canvas id="canvas" width="320" height="320"></canvas>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="SubmitButton">Register</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>

    <div class="container">
        <?php if($user != null): ?>
        <div class="input-group">
            <span class="input-group-btn">
                <button class="btn btn-lg btn-primary" type="submit" style="height: 45px" id="searchBtn">Search!</button>
            </span>
            <div class="input-group input-group-lg">
                <span class="input-group-addon" id="sizing-addon1">Price</span>
                <input type="text" class="form-control" value="Price" aria-describedby="sizing-addon1" disabled="true" id="PriceText">
            </div>
        </div>
        <table class="table table-hover">
            <?php
                echo "<th>Param</th>";
                echo "<th>Input</th>";
                foreach ($PREDICTION_PARAM as $param) {
                    echo "<tr>";
                    echo "<td>".$param."</td>";
                    echo "<td><input type=\"text\" class=\"form-control\" placeholder=\"$param\" id=\"$param\"></td>";
                    echo "</tr>";
                }
            ?>
        </table>
        <?php else:?>
            <h1>欢迎来到二手车交易市场！请您先登录～</h1>
        <?php endif;?>
    </div>

    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/JavaScript">
        var video = document.getElementById('video');
        
	window.addEventListener("DOMContentLoaded", function(){
            navigator.getUserMedia = navigator.getUserMedia ||
                navigator.webkitGetUserMedia ||
                navigator.mozGetUserMedia;

            if (navigator.getUserMedia) {
                navigator.getUserMedia({video:true},
                    function(stream) {
                        video.src = window.URL.createObjectURL(stream);
                        video.onloadedmetadata = function(e) {
                            video.play();
                        };
                    },
                    function(err) {
                        alert(err.name);
                    }
                );
            }
        });
	
        $("#searchBtn").click(function () {

           var paramArray = [
               "symboling",
               "normalized-losses",
               "make",
               "fuel-type",
               "aspiration",
               "num-of-doors",
               "body-style",
               "drive-wheels",
               "engine-location",
               "wheel-base",
               "length",
               "width",
               "height",
               "curb-weight",
               "engine-type",
               "num-of-cylinders",
               "engine-size",
               "fuel-system",
               "bore",
               "stroke",
               "compression-ratio",
               "horsepower",
               "peak-rpm",
               "city-mpg",
               "highway-mpg",
               "price"
           ];

           var data = {};

           for(var i=0; i<paramArray.length; i++) {
               data[paramArray[i]] = $("#"+paramArray[i]).val();
           }
            $.ajax({
                type: "POST",
                url: "/prediction.php",
                dataType : "json",
                data: data,
                success: function(msg){
                    if(msg.msg == "success") {
                        var price = document.getElementById("PriceText");
                        price.value = msg.price;
                        alert("success!");
                    }
                    else {
                        alert("Search failed!");
                    }
                }
            });

        });

        $("#loginBtn").click(function () {
            var title = document.getElementById("myModalLabel");
            title.innerHTML = "Login";
            var btn = document.getElementById("SubmitButton");
            btn.innerHTML = "Login";
        });

        $("#registerBtn").click(function () {
            var title = document.getElementById("myModalLabel");
            title.innerHTML = "Register";
            var btn = document.getElementById("SubmitButton");
            btn.innerHTML = "Register";
        });

        var image_code;
        $("#camera").click(function(){
            var canvas = document.getElementById('canvas');
            var context2D = canvas.getContext("2d");
            context2D.fillStyle = "#ffffff";
            context2D.fillRect(0, 0, 320, 320);
            context2D.drawImage(video, 0, 0, 320, 320);
            image_code =canvas.toDataURL("image/png");
        });

        function isValid(studentID, image_code) {
            var reg = new RegExp("^[0-9]{10}$");
            if(studentID == "" || !reg.test(studentID)) {
                return false;
            }
            if(!image_code) {
                return false;
            }
            return true;
        }

        function clearInput() {
            var canvas = document.getElementById('canvas');
            var context2D = canvas.getContext("2d");
            context2D.clearRect(0, 0, 320, 320);

            var idInput = document.getElementById("studentID");
            idInput.value = "";
        }

        $("#SubmitButton").click(function () {
            var studentID = $("#studentID").val();
            if(!isValid(studentID, image_code)) {
                alert("input invalid!");
                return;
            }
            var btn = document.getElementById("SubmitButton");
            if(btn.innerHTML == "Register") {
                var data = {"studentID":studentID, "image_code":image_code};
                $.ajax({
                    type: "POST",
                    url: "/register.php",
                    dataType : "json",
                    data: data,
                    success: function(msg){
                        if(msg.msg == "success") {
                            alert("StudentId:"+studentID+" Register success");
                            $("#myModal").modal('hide');
                        }
                        else {
                            alert("Register failed!");
                        }
                        clearInput();
                    }
                });
            }
            else if(btn.innerHTML == "Login") {
                var data = {"studentID":studentID, "image_code":image_code};
                $.ajax({
                    type: "POST",
                    url: "/login.php",
                    dataType : "json",
                    data: data,
                    success: function(msg){
                        if(msg.msg == "success") {
                            alert("Login success");
                            $("#myModal").modal('hide');
                            window.location.href='/';
                        }
                        else {
                            alert("Login failed!");
                        }
                        clearInput();
                    }
                });
            }
        });
    </script>
</body>
</html>
