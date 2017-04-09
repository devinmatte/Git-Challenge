<?php ?>
<!DOCTYPE HTML>
<!--
    Template:
    Stellar by HTML5 UP
    html5up.net | @ajlkn
    Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
<head>
    <title>Git Challenge</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!--[if lte IE 8]>
    <script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/jquery.scrolly.min.js"></script>
    <script src="assets/js/skel.min.js"></script>
    <script src="assets/js/util.js"></script>
    <!--[if lte IE 8]>
    <script src="assets/js/ie/respond.min.js"></script>
    <![endif]-->
    <script src="assets/js/main.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/main.css"/>
    <link rel="stylesheet" href="assets/css/git-challenge.css"/>
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="assets/css/ie9.css"/><![endif]-->
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="assets/css/ie8.css"/><![endif]-->
</head>

<?php

$configs = require("include/configuration.php");
require("connection.php");
$connection = new Connection;
$conn = $connection->initialize();

$alert = new Alert;

$call_count = 0;

?>
<body>


<!-- Wrapper -->
<div id="wrapper">

    <!-- Header -->
    <header id="header" class="alt">
        <h1 class="fa fa-git-square">Challenge</h1>
    </header>

    <!-- Nav -->
    <nav id="nav">
        <ul>
            <li><a href="#debug">Debugging</a></li>
        </ul>
    </nav>

    <!-- Main -->
    <div id="main">

        <?php
        if ($configs->options->debug == false) {
            echo "<!--";
        }
        ?>

        <section id="debug" class="main special">
            <header class="major">
                <h2>Debugging</h2>
            </header>

            <?php

            require("main.php");
            $main = new main($conn, $configs, $alert)

            ?>


            <footer class="major">
            </footer>
        </section>

        <?php
        if ($configs->options->debug == false) {
            echo "-->";
        }
        ?>

    </div>

    <!-- Footer -->
    <footer id="footer">
        <ul class="icons">
            <li><a href="https://github.com/devinmatte/Git-Challenge" class="icon alt fa-github"><span class="label">GitHub</span></a>
            </li>
        </ul>
        <p class="copyright">&copy; Devin Matte. Design: <a href="https://html5up.net">HTML5 UP</a>.</p>
    </footer>

</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
