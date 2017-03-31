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
    <script src="assets/js/ie/respond.min.js"></script><![endif]-->
    <script src="assets/js/main.js"></script>
    <!-- Latest compiled and minified CSS
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/main.css"/>
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="assets/css/ie9.css"/><![endif]-->
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="assets/css/ie8.css"/><![endif]-->
</head>

<?php

include("include/configuration.php");

// Create connection
$conn = new mysqli(CONF_LOCATION, CONF_ADMINID, CONF_ADMINPASS);

if(DEBUG == "OFF"){
    echo "<!--";
}

// Check connection
if ($conn->connect_error) {
    die("<div class=\"alert alert-danger alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a><b>Connection failed:</b> " . $conn->connect_error . "</div>");
}

// Create database
$sql = "CREATE DATABASE Git-Challenge";
if ($conn->query($sql) === TRUE) {
    echo "<div class=\"alert alert-success alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Database created successfully</div>";
}

$conn = new mysqli(CONF_LOCATION, CONF_ADMINID, CONF_ADMINPASS, CONF_DATABASE);

// sql to create table
$sql = "CREATE TABLE Tracked (sha VARCHAR(256) NOT NULL)";

if ($conn->query($sql) === TRUE) {
    echo "<div class=\"alert alert-success alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Table <i>Tracked</i> created successfully</div>";
}

// sql to create table
$sql = "CREATE TABLE Users (name VARCHAR(256) NOT NULL, username VARCHAR(128) NOT NULL, id INT(35) NOT NULL, score INT(25) DEFAULT 0, added INT(25) DEFAULT 0, removed INT(25) DEFAULT 0, challenge INT(25) DEFAULT 0)";

if ($conn->query($sql) === TRUE) {
    echo "<div class=\"alert alert-success alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Table <i>Users</i> created successfully</div>";
}

/*
$sql = "INSERT INTO Tracked (sha) VALUES ('')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
*/


$url = "https://api.github.com/users/" . GIT_ORG . "/repos" . "?client_id=" . GIT_CLIENT . "&client_secret=" . GIT_SECRET;
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
    $repo_url = substr($repo->commits_url, 0, -6) . "?client_id=" . GIT_CLIENT . "&client_secret=" . GIT_SECRET;
    $repo_json = file_get_contents($repo_url, false, stream_context_create($opts));
    $repo_obj = json_decode($repo_json);


    //Loop through all Commits in each Repo
    foreach ($repo_obj as &$commit) {
        $query = "SELECT * FROM Users WHERE id='" . $commit->author->id . "'";
        $result = $conn->query($query);

        if (SIGN_UP == "FALSE" && $result->num_rows <= 0) {
            $sql = "INSERT INTO Users (name, username, id) VALUES ('" . $commit->commit->author->name . "', '" . $commit->author->login . "', '" . $commit->author->id . "')";
            if ($conn->query($sql) === TRUE) {
                echo "<div class=\"alert alert-info alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>New record created successfully in User: " . $commit->commit->author->name . "</div>";
            } else {
                echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error: " . $sql . "<br>" . $conn->error . "</div>>";
            }
        }


        if ($result->num_rows > 0) {

            $query = "SELECT sha FROM Tracked WHERE sha='" . $commit->sha . "'";

            $result = $conn->query($query);
            if ($result->num_rows <= 0) {
                //Getting Proper Results
                $commit_url = $commit->url . "?client_id=" . GIT_CLIENT . "&client_secret=" . GIT_SECRET;

                $commit_json = file_get_contents($commit_url, false, stream_context_create($opts));
                $commit_obj = json_decode($commit_json);
                $query = "SELECT * FROM Users WHERE id='" . $commit->author->id . "'";

                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();

                    //Count total stats for each Commit to their corresponding person
                    $score = $user["score"] + ($commit_obj->stats->additions + $commit_obj->stats->deletions);
                    $sql = "UPDATE Users SET score=" . $score . " WHERE id='" . $commit->author->id . "'";
                    if ($conn->query($sql) === FALSE) {
                        echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error updating record: " . $conn->error . "</div>";
                    }

                    //Count added stats for each Commit to their corresponding person
                    $added = $user["added"] + $commit_obj->stats->additions;
                    $sql = "UPDATE Users SET added=" . $added . " WHERE id='" . $commit->author->id . "'";
                    if ($conn->query($sql) === FALSE) {
                        echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error updating record: " . $conn->error . "</div>";
                    }

                    //Count removed stats for each Commit to their corresponding person
                    $removed = $user["removed"] + $commit_obj->stats->deletions;
                    $sql = "UPDATE Users SET removed=" . $removed . " WHERE id='" . $commit->author->id . "'";
                    if ($conn->query($sql) === FALSE) {
                        echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error updating record: " . $conn->error . "</div>";
                    }
                    $sql = "INSERT INTO Tracked VALUES ('" . $commit_obj->sha . "')";
                    if ($conn->query($sql) === TRUE) {
                        echo "<div class=\"alert alert-info alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>New record created successfully in Tracked: " . $commit_obj->sha . "</div>";
                    } else {
                        echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error: " . $sql . "<br>" . $conn->error . "</div>";
                    }

                }
            }
        }
    }
}

if(DEBUG == "OFF"){
    echo "-->";
}

?>
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
            <?php
            $query = "SELECT * FROM Users ORDER BY score DESC";
            $result = $conn->query($query);

            for ($row = 0; $row < 5; $row++) {
                $user = $result->fetch_assoc();
                echo "<tr>";
                echo "<td>" . ($row + 1) . "</td>";
                echo "<td>" . $user["name"] . "</td>";
                echo "<td>" . $user["score"] . "</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </header>

    <!-- Nav -->
    <nav id="nav">
        <ul>
            <li><a href="#intro" class="active">Introduction</a></li>
            <li><a href="#breakdown">Point Breakdown</a></li>
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
                        <h2>Idea</h2>
                    </header>
                    <p>Git Challenge was a project I had an idea for when I looked over a GitHub Organisation I was a
                        part of. It is for my old High School Technology Team, the organisation that taught me most of
                        what I knew about programming before I came here. The projects in the GitHub hadn't been touched
                        by anyone except myself and a few other Team Alumni. So I thought I should come up with a way to
                        encourage contributing to these projects, and to teach people git. So I came up with
                        Git-Challenge. A app made to gamify contributing to projects, for any Organisation. Not just
                        this Tech Team. It could be used for CSH, or really any other git organisation with multiple
                        contributors.</p>
                    <ul class="actions">
                        <li><a href="generic.html" class="button">Learn More</a></li>
                    </ul>
                </div>
                <span class="image"><img
                            src="https://static1.squarespace.com/static/5783a7e19de4bb11478ae2d8/5821d2b909e1c46748736b4a/583d6f01e58c627c3a6b7e47/1486468532983/Github_Blog.gif?w=1000w"
                            alt=""/></span>
            </div>
        </section>

        <!-- Breakdown Section -->
        <section id="breakdown" class="main special">
            <header class="major">
                <h2>Point Breakdown</h2>
            </header>
            <table class="alt">
                <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Score</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $query = "SELECT * FROM Users ORDER BY score DESC";
                $result = $conn->query($query);

                for ($row = 0; $row < $result->num_rows; $row++) {
                    $user = $result->fetch_assoc();
                    echo "<tr>";
                    echo "<td>" . ($row + 1) . "</td>";
                    echo "<td>" . $user["name"] . "</td>";
                    echo "<td>" . $user["score"] . "<div class=\"progress\">
  <div class=\"progress-bar progress-bar-success active fa fa-plus-circle\" role=\"progressbar\" style=\"width:" . ($user["added"] / $user["score"]) * 100 . "%\">
  </div>
  <div class=\"progress-bar progress-bar-danger active fa fa-minus-circle\" role=\"progressbar\" style=\"width:" . ($user["removed"] / $user["score"]) * 100 . "%\">
  </div>
  <div class=\"progress-bar progress-bar-info active fa fa-upload\" role=\"progressbar\" style=\"width:" . (0 / $user["score"]) * 100 . "%\">
  </div>
  <div class=\"progress-bar progress-bar-warning active fa fa-trophy\" role=\"progressbar\" style=\"width:" . ($user["challenge"] / $user["score"]) * 100 . "%\">
  </div>
</div>" . "</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
            <footer class="major">
                <ul class="actions">
                    <li><a href="generic.html" class="button">Learn More</a></li>
                </ul>
            </footer>
        </section>

        <!-- Second Section -->
        <section id="second" class="main special">
            <header class="major">
                <h2>Ipsum consequat</h2>
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

</body>
</html>

<?php
mysqli_close($conn);
?>
