<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}" />

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

{{--                <form method="post" action="{{ route('contracts.resolve') }}" target="_blank">--}}
{{--                    <fieldset>--}}
{{--                        <legend>Resolve Lawsuit (first phase)</legend>--}}
{{--                        <label>Plaintiff<input type="text" name="plaintiff[signatures]" value="KN"></label>--}}
{{--                        <label>Defendant<input type="text" name="defendant[signatures]" value="NNV"></label>--}}
{{--                        <input type="submit" value="Get result" class="btn-submit">--}}
{{--                    </fieldset>--}}
{{--                </form>--}}
                <fieldset>
                    <legend>Resolve Lawsuit (first phase)</legend>
                    <label>Plaintiff<input type="text" name="plaintiffSignaturesWin" value="KN"></label>
                    <label>Defendant<input type="text" name="defendantSignaturesWin" value="NNV"></label>
                    <input type="submit" value="Get result" class="btn-submit-win">
                </fieldset>

                <br><br><br>

                <fieldset>
                    <legend>Calculate minimum points to win the trial (second stage)</legend>
                    <label>Plaintiff<input type="text" name="plaintiffSignaturesPoints" value="N#V"></label>
                    <label>Defendant<input type="text" name="defendantSignaturesPoints" value="NVV"></label>
                    <input type="submit" value="Get result" class="btn-submit-points">
                </fieldset>
                <br><br>

                <a href="reports/index.html" target="_blank">Code Coverage</a>
            </div>
        </div>
    </body>
</html>

<script type="text/javascript">

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".btn-submit-win").click(function(e){
        e.preventDefault();

        var plaintiff = {
            "signatures": $("input[name=plaintiffSignaturesWin]").val()
        }

        var defendant = {
            "signatures": $("input[name=defendantSignaturesWin]").val()
        }

        $.ajax({
            type:'POST',
            url: '/api/contracts',
            dataType: "json",
            data:{
                plaintiff,
                defendant
            },
            success:function(response){
                console.log(response.data);
                alert(response.data.winner);
            },
            error: function(XMLHttpRequest) {
                console.log(XMLHttpRequest);
                alert(XMLHttpRequest.responseText);
            }
        });
    });

    $(".btn-submit-points").click(function(e){
        e.preventDefault();

        var plaintiff = {
            "signatures": $("input[name=plaintiffSignaturesPoints]").val()
        }

        var defendant = {
            "signatures": $("input[name=defendantSignaturesPoints]").val()
        }

        $.ajax({
            type:'POST',
            url: '/api/contracts/calculate/points_to_win',
            dataType: "json",
            data:{
                plaintiff,
                defendant
            },
            success:function(response){
                console.log(response.data);
                alert(response.data.result);
            },
            error: function(XMLHttpRequest) {
                console.log(XMLHttpRequest);
                alert(XMLHttpRequest.responseText);
            }
        });
    });

</script>
