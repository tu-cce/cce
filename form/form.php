<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <!-- Local CSS -->
    <link rel="stylesheet" type="text/css" href="D:\tu-cce\static\master.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
    <!-- JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


</head>
<body>
    <div align="center">
        <div class="col-6">
            <form method="POST">
                <br>
                <div class="form-group">
                    <input type="text" class="form-control" id="authors" name="authors" placeholder="Authors"><br>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" id="keywords" name="keywords" placeholder="Keywords"><br>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" id="title" name="title" placeholder="Title"><br>
                </div>

                <!-- <div class="form-group">
                    <input type="text" class="form-control-lg" id="abstract" name="abstract" placeholder="Abstract"><br>
                </div> -->

                <div class="inputgroup input-group-lg">
                    <textarea class="form-control" name="abstract" placeholder="Abstract" rows="10" cols="35"></textarea>
                </div>
                <br><br>

                <div class="form-group">
                    <input type="text" class="form-control" id="number" name="number" placeholder="Article number"><br>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" id="edition" name="edition" placeholder="Edition[e.g. 2013/1]"><br>
                </div>

                <!-- <button id="button" type="submit" name="submit">Make an Entry</button> -->
                <button id="button" type="submit" name="submit" class="btn btn-outline-primary">Make an Entry</button>
            </form>
        </div>
    </div>
    
    <div id="form_results">
        <br><br><br>
        <!-- Results will be displayed here, when a search is made. -->
        <div align="center">
            <?php
                if(isset($_POST['submit'])){ include("form_php.php"); } 
            ?>
        </div>
        
    </div>
</body>
</html>