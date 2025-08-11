<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="{{ asset('img/logo esgpbg.ico') }}" type="image/x-icon">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
@if (session('msg'))
<script>
Swal.fire({
  icon: 'error',
  title: 'Oops...',
  text: 'username/password salah!',
})
</script>
@endif

<div class="container" id="container">
    <div class="form-container sign-in-container" style="background-color: #0b1826;">
        <div>
            <img class="displayed" src="{{ asset('img/logo esgpbg.png') }}" style="width:100px">
            <br><br><br><br>
            <span class="apps">TOKO EMAS</span>
            <h5 class="center-text" style="margin-top: 70px">PT ELING SAMBAS GRUP</h5>
            <h5 class="center-text">&copy; <script>document.write(new Date().getFullYear())</script></h5>
        </div>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <form style="background-color: transparent;" action="{{ route('login.proses') }}" method="POST">
                @csrf
                <div class="overlay-panel overlay-right">   
                    <h1 style="color: #0b1826;">Sign in</h1>
                    <div class="social-container">
                        <a href="#" class="social"></a>
                        <a href="#" class="social"></a>
                        <a href="#" class="social"></a>
                    </div>
                    <input type="text" placeholder="Username" name="username" autofocus />
                    <input type="password" placeholder="Password" name="password" />
                    <br>
                    <button type="submit">Sign In</button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer>
    <p></p>
</footer>

<script>
const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

if(signUpButton && signInButton) {
    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
}
</script>
</body>
</html>
