<?php

include("user.php");

//start the session
session_start();

$fullArray = [["Devin Matte", 98765]];
array_push($fullArray, ["Luke Gaynor", 21212]);
array_push($fullArray, ["James Sonne", 0]);

$test_user = new user("Devin Matte", "devinmatte@gmail.com", "devinmatte", 0);
$test_user2 = new user("James Sonne", "test@gmail.com", "test", 0);

$user_array = array($test_user, $test_user2);


$url = "https://api.github.com/repos/NHSTechTeam/Calendar-Maker/commits/dcad3fb6cce3e22fa940cd4a13369820e6f789ec";
$opts = [
    'http' => [
        'method' => 'GET',
        'header' => [
            'User-Agent: PHP'
        ]
    ]
];

$json = file_get_contents($url, false, stream_context_create($opts));
$obj = json_decode($json);

//Loop through all Repos in Org
//Loop through all Commits in each Repo
//Count stats for each Commit to their corresponding person


?>

    <!DOCTYPE HTML>
    <!--
        Stellar by HTML5 UP
        html5up.net | @ajlkn
        Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
    -->
    <html>
    <head>
        <title>Stellar by HTML5 UP</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <!--[if lte IE 8]>
        <script src="assets/js/ie/html5shiv.js"></script><![endif]-->
        <link rel="stylesheet" href="assets/css/main.css"/>
        <!--[if lte IE 9]>
        <link rel="stylesheet" href="assets/css/ie9.css"/><![endif]-->
        <!--[if lte IE 8]>
        <link rel="stylesheet" href="assets/css/ie8.css"/><![endif]-->
    </head>
    <body>

    <!-- Wrapper -->
    <div id="wrapper">

        <!-- Header -->
        <header id="header" class="alt">
            <h1 class="fa fa-git-square">Challenge</h1>
            <p><?php echo($obj->stats->total); ?></p>
                <table class="alt">
                    <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Name</th>
                        <th>Score</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php for ($row = 0; $row < 5; $row++): ?>
                        <tr>
                            <td><?php echo $row + 1 ?></td>
                                <td><?php echo $user_array[$row]->name; ?></td>
                                <td><?php echo $user_array[$row]->score; ?></td>
                        </tr>
                    <?php endfor; ?>
                    </tbody>
                </table>
        </header>

        <!-- Nav -->
        <nav id="nav">
            <ul>
                <li><a href="#intro" class="active">Introduction</a></li>
                <li><a href="#second">Second Section</a></li>
                <li><a href="#cta">Get Started</a></li>
            </ul>
        </nav>

        <!-- Main -->
        <div id="main">

            <!-- Introduction -->
            <section id="intro" class="main">
                <div class="spotlight">
                    <div class="content">
                        <header class="major">
                            <h2>Ipsum sed adipiscing</h2>
                        </header>
                        <p>Sed lorem ipsum dolor sit amet nullam consequat feugiat consequat magna
                            adipiscing magna etiam amet veroeros. Lorem ipsum dolor tempus sit cursus.
                            Tempus nisl et nullam lorem ipsum dolor sit amet aliquam.</p>
                        <ul class="actions">
                            <li><a href="generic.html" class="button">Learn More</a></li>
                        </ul>
                    </div>
                    <span class="image"><img src="images/pic01.jpg" alt=""/></span>
                </div>
            </section>

            <!-- Second Section -->
            <section id="second" class="main special">
                <header class="major">
                    <h2>Ipsum consequat</h2>
                    <p>Donec imperdiet consequat consequat. Suspendisse feugiat congue<br/>
                        posuere. Nulla massa urna, fermentum eget quam aliquet.</p>
                </header>
                <ul class="statistics">
                    <li class="style1">
                        <span class="icon fa-code-fork"></span>
                        <strong>5,120</strong> Etiam
                    </li>
                    <li class="style2">
                        <span class="icon fa-folder-open-o"></span>
                        <strong>8,192</strong> Magna
                    </li>
                    <li class="style3">
                        <span class="icon fa-signal"></span>
                        <strong>2,048</strong> Tempus
                    </li>
                    <li class="style4">
                        <span class="icon fa-laptop"></span>
                        <strong>4,096</strong> Aliquam
                    </li>
                    <li class="style5">
                        <span class="icon fa-diamond"></span>
                        <strong>1,024</strong> Nullam
                    </li>
                </ul>
                <p class="content">Nam elementum nisl et mi a commodo porttitor. Morbi sit amet nisl eu arcu faucibus
                    hendrerit vel a risus. Nam a orci mi, elementum ac arcu sit amet, fermentum pellentesque et purus.
                    Integer maximus varius lorem, sed convallis diam accumsan sed. Etiam porttitor placerat sapien, sed
                    eleifend a enim pulvinar faucibus semper quis ut arcu. Ut non nisl a mollis est efficitur
                    vestibulum.
                    Integer eget purus nec nulla mattis et accumsan ut magna libero. Morbi auctor iaculis porttitor. Sed
                    ut
                    magna ac risus et hendrerit scelerisque. Praesent eleifend lacus in lectus aliquam porta. Cras eu
                    ornare
                    dui curabitur lacinia.</p>
                <footer class="major">
                    <ul class="actions">
                        <li><a href="generic.html" class="button">Learn More</a></li>
                    </ul>
                </footer>
            </section>

        </div>

        <!-- Footer -->
        <footer id="footer">
            <p class="copyright">&copy; Devin Matte. Design: <a href="https://html5up.net">HTML5 UP</a>.</p>
        </footer>

    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/jquery.scrolly.min.js"></script>
    <script src="assets/js/skel.min.js"></script>
    <script src="assets/js/util.js"></script>
    <!--[if lte IE 8]>
    <script src="assets/js/ie/respond.min.js"></script><![endif]-->
    <script src="assets/js/main.js"></script>

    </body>
    </html>

<?php

?>