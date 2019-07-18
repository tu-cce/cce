<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CCE</title>

    <!-- Fonts and whatnot -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Sanchez&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/fdf778c62d.js"></script>

    <!-- Local css -->
    <style>
        <?php
        include "static/style_article.css";
        include "static/style_article.css"
        ?>
    </style>

</head>
<body>

<div class="search-sec">
    <nav>
        <form method="POST">
            <fieldset class="nav-search">
                <label class="search"><strong>Find an Article</strong></label>
                <input class="search" type="text" id="title" name="title" placeholder="Title">
                <input class="search" type="text" id="first" name="first" placeholder="First name">
                <input class="search" type="text" id="last" name="last" placeholder="Last name">
                <input class="search" type="text" id="keywords" name="keywords" placeholder="Keywords">
                <input class="search" type="text" id="edition" name="edition" placeholder="Edition[e.g. 2013/1]">
                <button class="search-btn" id="button" type="submit" name="submit"><i class="fas fa-search"></i>
                </button>
            </fieldset>
        </form>
    </nav>
</div>

<div class="search-sec-mobile">
    <nav>
        <label for="toggle"> &#128269;</label>
        <input type="checkbox" id="toggle"/>
        <div class="menu">
            <form method="POST">
                <fieldset class="nav-search">
                    <input class="search" type="text" id="title" name="title" placeholder="Title">
                    <input class="search" type="text" id="first" name="first" placeholder="First name">
                    <input class="search" type="text" id="last" name="last" placeholder="Last name">
                    <input class="search" type="text" id="keywords" name="keywords" placeholder="Keywords">
                    <input class="search" type="text" id="edition" name="edition" placeholder="Edition[e.g. 2013/1]">
                    <button class="search-btn" id="button" type="submit" name="submit"><i class="fas fa-search"></i>
                    </button>
                </fieldset>
            </form>
        </div>
    </nav>
</div>

<main>
    <h1><strong> Computer & Communications Engineering</strong></h1>
    <div id="results">
        <!-- Results will be displayed here, when a search is made. -->
        <?php
        if (isset($_POST['submit'])) {
            include("results.php");
        }
        ?>
        
    </div>
</main>

</body>
</html>