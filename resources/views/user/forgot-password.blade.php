<!DOCTYPE html>
<html>
<head>
<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<meta charset="UTF-8">
<title>KK APP- User Login</title>
<style>
body {
    /*background: url('http://i.imgur.com/Eor57Ae.jpg') no-repeat fixed center center;*/
    /*background-size: cover;*/
    background: #59423c;
    font-family: Montserrat;
}

.logo {
    width: 213px;
    height: 36px;
    /*background: url('../public/admin/images/logo.png') no-repeat;*/
    margin: 30px auto;
}

.login-block {
    width: 320px;
    padding: 20px;
    background: #fff;
    border-radius: 5px;
    border-top: 5px solid #52154e;
    margin: 0 auto;
}

.login-block h1 {
    text-align: center;
    color: #000;
    font-size: 18px;
    text-transform: uppercase;
    margin-top: 0;
    margin-bottom: 20px;
}

.login-block input {
    width: 100%;
    height: 42px;
    box-sizing: border-box;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-bottom: 20px;
    font-size: 14px;
    font-family: Montserrat;
    padding: 0 20px 0 50px;
    outline: none;
}

.login-block input#email {
    background: #fff url('http://i.imgur.com/u0XmBmv.png') 20px top no-repeat;
    background-size: 16px 80px;
}

.login-block input#email:focus {
    background: #fff url('http://i.imgur.com/u0XmBmv.png') 20px bottom no-repeat;
    background-size: 16px 80px;
}

.login-block input#password {
    background: #fff url('http://i.imgur.com/Qf83FTt.png') 20px top no-repeat;
    background-size: 16px 80px;
}

.login-block input#password:focus {
    background: #fff url('http://i.imgur.com/Qf83FTt.png') 20px bottom no-repeat;
    background-size: 16px 80px;
}

.login-block input:active, .login-block input:focus {
    border: 1px solid #ff656c;
}

.login-block button {
    width: 100%;
    height: 40px;
    background: #52154e;
    box-sizing: border-box;
    border-radius: 5px;
    border: 1px solid #52154e;
    color: #fff;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 14px;
    font-family: Montserrat;
    outline: none;
    cursor: pointer;
}

.login-block button:hover {
    background: #ff7b81;
}

.msg-error{
    color: red;
}

</style>
</head>

<body>

<div class="logo"></div>
<div class="login-block">
    <h1>Reset Password</h1>
    @include('partials.errors')
    <span class="msg-error">{{ Session::get('error') }}</span>
    <form action="{{url('user/forgot/password')}}" method="post">
        <input type="email" value="" placeholder="Email" id="email" name="email" required="required" />
        <button>Submit</button>
    </form>
</div>
</body>

</html>