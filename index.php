<?php

include("user.php");
include("include/configuration.php");

//start the session
session_start();

//TODO: SQL Database
// Create connection
$conn = new mysqli(CONF_LOCATION, CONF_ADMINID, CONF_ADMINPASS);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully\n";

// Create database
$sql = "CREATE DATABASE Git-Challenge";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . $conn->error . "\n";
}

$conn = new mysqli(CONF_LOCATION, CONF_ADMINID, CONF_ADMINPASS, CONF_DATABASE);

// sql to create table
$sql = "CREATE TABLE Tracked (sha VARCHAR(256) NOT NULL)";

if ($conn->query($sql) === TRUE) {
    echo "Table Tracked created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

// sql to create table
$sql = "CREATE TABLE Users (name VARCHAR(256) NOT NULL, email VARCHAR(128) NOT NULL, score INT(25))";

if ($conn->query($sql) === TRUE) {
    echo "Table Users created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

/*
$sql = "INSERT INTO Tracked (sha) VALUES ('')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
*/

$test_user = new user("Devin Matte", "devinmatte@gmail.com", "devinmatte", 0);
$test_user2 = new user("James Sonne", "test@gmail.com", "test", 0);

$user_array = array($test_user, $test_user2);

$url = "https://api.github.com/users/" . GIT_ORG . "/repos";
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
foreach ($obj as &$repo) {
    $repo_url = substr($repo->commits_url, 0, -6);
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => [
                'User-Agent: PHP'
            ]
        ]
    ];
    $repo_json = file_get_contents($repo_url, false, stream_context_create($opts));
    $repo_obj = json_decode($repo_json);


    /*
    //Loop through all Commits in each Repo
    foreach ($repo_obj as &$commit) {
        //TODO: Check if Sha is in Database
        //TODO: If Sha is in Database, skip

        $query = "SELECT sha from Tracked where sha=" . $commit->sha;

        if ($conn->query($query) === 0) {
            $commit_url = $commit->url;
            $opts = [
                'http' => [
                    'method' => 'GET',
                    'header' => [
                        'User-Agent: PHP'
                    ]
                ]
            ];
            $sql = "INSERT INTO Tracked (sha) VALUES (" . $commit->sha . ")";
            $conn->query($sql);
            $commit_json = file_get_contents($commit_url, false, stream_context_create($opts));
            $commit_obj = json_decode($commit_json);

            //Count stats for each Commit to their corresponding person
            foreach ($commit_obj as &$single_commit) {
                echo $single_commit->stats->total;
                $key = array_search($test_user, $user_array);
                $user_array[$key]->score += $single_commit->stats->total;
            }
        }
    }
    */
}

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
mysqli_close($conn);
?>