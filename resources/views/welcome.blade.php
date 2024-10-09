<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hyperion Aggregator</title>
    <link rel="shortcut icon" href="https://itpi.co.id/wp-content/uploads/2020/07/ITPI-THEME-THUMBNAIL.jpg"
        type="image/jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.6.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(-45deg, #ff8b1e, #fdc330, #4da5ee, #126bb4);
            background-size: 400% 400%;
            animation: gradient 7s ease infinite;
            height: 100vh;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .btn {
            background-image: linear-gradient(to right, #fdc330 0%, #fd9330 51%, #fdc330 100%);
            color: #3f3f3f;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-sm-10">
                <div class="card text-center py-4 shadow-lg border-0">
                    <div class="card-body pb-3 pt-5">
                        <div class="row justify-content-center">
                            <div class="col-sm-8">
                                <img src="{{ asset('img/logo.svg') }}" alt="logo" style="width: 80%;">
                                <hr>
                                <p class="card-text">
                                    Hyperion merupakan API Gateway Aggregator dari project service yang ada pada PT ITPI
                                    Technology Indonesia. Hyperion memiliki fungsi menjadi gateway akses satu pintu
                                    untuk semua service yang dimiliki oleh setiap project dari PT ITPI Technology
                                    Indonesia.
                                </p>
                                <a href="https://documenter.getpostman.com/view/6631471/2s8YRqkWU8" target="_blank"
                                    class="btn">
                                    <i class="fa fa-book me-2"></i>
                                    Lihat Dokumentasi
                                </a>
                                <a href="{{ route('login') }}" class="btn">
                                    <i class="fa fa-sign-in-alt me-2"></i>
                                    Login
                                </a>
                                <div class="text-center mt-5 text-dark p-3 border rounded">
                                    <strong>
                                        <i class="fab fa-laravel text-danger me-2"></i> Laravel
                                        v{{ Illuminate\Foundation\Application::VERSION }}
                                    </strong>
                                    <span class="mx-2">|</span>
                                    <strong>
                                        <i class="fab fa-php text-primary me-2"></i>
                                        (PHP v{{ PHP_VERSION }})
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
</body>

</html>
