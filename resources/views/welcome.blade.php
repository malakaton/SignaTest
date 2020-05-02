<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
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
        <div class="flex-center position-ref full-height">

            <div class="content">
                <div class="title m-b-md">
                    Looby Wars
                </div>

                <form method="post" action="{{ route('contracts.resolve') }}" target="_blank">
                    <fieldset>
                        <legend>Resolve Lawsuit (first phase)</legend>
                        <label>Plaintiff<input type="text" name="plaintiff[signatures]" value="KN"></label>
                        <label>Defendant<input type="text" name="defendant[signatures]" value="NNV"></label>
                        <input type="submit" value="Get result">
                    </fieldset>
                </form>

                <br><br><br>

                <form method="post" action="{{ route('contracts.resolve') }}" target="_blank">
                    <fieldset>
                        <legend>Calculate minimum points to win the trial (second stage)</legend>
                        <label>Plaintiff<input type="text" name="plaintiff[signatures]" value="N#V"></label>
                        <label>Defendant<input type="text" name="defendant[signatures]" value="NVV"></label>
                        <input type="submit" value="Get result">
                    </fieldset>
                </form>
            </div>
        </div>
    </body>
</html>
