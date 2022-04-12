<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Mandarina</title>

        <!-- Fonts -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 80vh;
                margin: 0;
            }

            .full-height {
                height: 80vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="container">
          @include('flash::message')
        </div>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class=" m-b-md">
                <br />
                <p>Ha finalizado el período de segmentación</p>
                </div>
                <div class="title m-b-md">
                    GRACIAS!<br />
                <img width="600px" src="images/logo_censo2022.jpg" alt="Censo 2022 República Argentina">
                </div>
            </div>
        </div>
        <div id="copyright" class="text-center justify-content-center"
        style="display:block"><hr />© 2022 INDEC - Argentina - Geoestadística</div>
        </div>
    </body>
</html>

