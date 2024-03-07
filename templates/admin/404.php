<?php
include('_config.php');

http_response_code(404);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $webname . $webmanagename; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            border: none;
            outline: none;
            box-sizing: border-box;
        }
        html, body {
            width: 100%;
            height: 100%;
        }
        body {
            background-color: #f1f1f1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .text__404 {
            color: #fff;
            font-size: 10em;
            font-weight: bold;
            font-family: Helvetica;
            text-shadow:
                0 1px 0 #ccc, 
                0 2px 0 #c9c9c9, 
                0 3px 0 #bbb, 
                0 4px 0 #b9b9b9, 
                0 5px 0 #aaa, 
                0 6px 1px rgba(0,0,0,.1), 
                0 0 5px rgba(0,0,0,.1), 
                0 1px 3px rgba(0,0,0,.3), 
                0 3px 5px rgba(0,0,0,.2), 
                0 5px 10px rgba(0,0,0,.25), 
                0 10px 10px rgba(0,0,0,.2), 
                0 20px 20px rgba(0,0,0,.15);
        }
        @media screen and (min-width: 1024px) {
            .text__404 {
                font-size: 16em;
            }
        }
    </style>
</head>
<body>
    <div class="text__404">404</div>
</body>
</html>
