<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    @include('auth.style')
    @notifyCss
</head>
<body>
    <div class="container">
        @include('notify::components.notify')
        <div class="screen">
            <div class="screen__content">
                <form class="login" method="POST" action="{{route('user.register')}}">
                    @csrf
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="text" class="login__input" placeholder="name" value="{{old('name')}}" name="name">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="email" class="login__input" placeholder="Email" value="{{old('email')}}" name="email">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-lock"></i>
                        <input type="password" class="login__input" placeholder="Password" value="{{old('password')}}" name="password">
                    </div>
                    <button class="button login__submit" type="submit">
                        <span class="button__text">Register</span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </button>				
                </form>
                <div class="social-login">
                    <div class="social-icons">
                        <a href="#" class="social-login__icon fab fa-instagram"></a>
                        <a href="#" class="social-login__icon fab fa-facebook"></a>
                        <a href="#" class="social-login__icon fab fa-twitter"></a>
                    </div>
                </div>
            </div>
            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape4"></span>
                <span class="screen__background__shape screen__background__shape3"></span>		
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>		
        </div>
    </div>
    @notifyJs
</body>
</html>