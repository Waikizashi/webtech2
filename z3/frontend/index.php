<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gm</title>
    <link rel="icon" href="/z3/frontend/assets/gm.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
</head>

<body>
    <style>
        canvas {
            width: 100%;
            height: auto;
            margin: 1px;
        }

        .card {
            margin-top: 20px;
        }

        .card-body {
            border: 2px solid grey;
            border-radius: 5px;
        }
    </style>
    </head>

    <body>
        <div class="container p-0">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="card p-2">
                        <div class="card-header px-2 py-2 mb-1 d-flex justify-content-between">
                            <div id="p1" class="alert alert-danger mb-0" role="alert">
                                Player 1
                            </div>
                            <div class="d-flex alert alert-warning mb-0" role="alert">
                                TIME: <h5 class="p-0 my-0 mx-2" id='timer'> 00 </h5>
                            </div>
                            <div id="p2" class="alert alert-success mb-0" role="alert">
                                Player 2
                            </div>
                        </div>
                        <div class="container mt-0">
                            <div id="startMenu" class="text-center">
                                <h1>Welcome</h1>
                                <button id="startButton" class="btn btn-primary my-3">Start</button>
                            </div>

                            <div id="winnerNotification" class="alert my-3" style="display: none;">
                                <strong>Winner: </strong> <span id="winnerName"></span>
                            </div>
                        </div>
                        <div class="p-0 card-body d-flex justify-content-center align-items-center">
                            <canvas style="display: none" id="gameCanvas" width="1200" height="1050"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="module" src='/z3/frontend/index.js'></script>

</html>