
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CCE</title>

    <!-- AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!--  Bootstrap CSS  -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!--  Bootstrap JS, jQuery and Popper.js  -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
    <!-- Local JS -->
    <script src="static/master.js"></script>

</head>
<body>

    <h1>Search for a CCE article</h1>

    <form method="POST">
        <input type="text" id="first" name="first" placeholder="First name">
        <input type="text" id="last" name="last" placeholder="Last name">
        <input type="text" id="keywords" name="keywords" placeholder="Keywords">
        <input type="text" id="title" name="title" placeholder="Title">
        <input type="text" id="edition" name="edition" placeholder="Edition[e.g. 2013/1]">
        <button id="button" type="submit" name="submit">Search</button>
    </form>

    <br>

    <div id="results">
        <!-- Results will be displayed here, when a search is made. -->
        <?php
            if(isset($_POST['submit'])){ include("results.php"); } 
        ?>
        
    </div>

    

</body>
</html>