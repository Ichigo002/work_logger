<?php include $config['LOGIN_PATH'].'header.phtml'; $f = "fff";?>
<body>
        <div class="loginform">
            <p class="title">SIGN IN</p>
            <form action="controllers/login-control.php" method="post">
                <input type="text" name="user" placeholder="User" required autofocus/>

                <input type="password" name="pass" placeholder="Password" required/>

                <input type="submit" value="Log In"/>
            </form>
        </div>
    </body>
</html>