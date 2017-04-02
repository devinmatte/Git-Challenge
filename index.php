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
$sql = "CREATE TABLE Tracked (sha VARCHAR(256), issueID VARCHAR(256))";

if ($conn->query($sql) === TRUE) {
    echo "<div class=\"alert alert-success alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Table <i>Tracked</i> created successfully</div>";
}

// sql to create table
$sql = "CREATE TABLE Users (name VARCHAR(256) NOT NULL, username VARCHAR(128) NOT NULL, id INT(35) NOT NULL, score INT(25) DEFAULT 0, added INT(25) DEFAULT 0, removed INT(25) DEFAULT 0, challenge INT(25) DEFAULT 0, commits INT(25) DEFAULT 0, issues INT(25) DEFAULT 0, pullRequests INT(25) DEFAULT 0)";

if ($conn->query($sql) === TRUE) {
    echo "<div class=\"alert alert-success alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Table <i>Users</i> created successfully</div>";
}

// sql to create table
$sql = "CREATE TABLE Stats (repository VARCHAR(256), commits INT(25) DEFAULT 0)";

if ($conn->query($sql) === TRUE) {
    echo "<div class=\"alert alert-success alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Table <i>Stats</i> created successfully</div>";
}

$call_count = 0;

//TODO: Define Functions for Refactor
function add_user($opts, $url)
{
    //TODO: Figure out why URL has an error
    $user_url = $url . "?client_id=" . GIT_CLIENT . "&client_secret=" . GIT_SECRET;
    $user_json = file_get_contents($user_url, false, stream_context_create($opts));
    $user_obj = json_decode($user_json);
    if ($user_obj->name != "") {
        $sql = "INSERT INTO Users (name, username, id) VALUES ('" . $user_obj->name . "', '" . $user_obj->login . "', '" . $user_obj->id . "')";
        if ($GLOBALS['conn']->query($sql) === TRUE) {
            $GLOBALS['call_count']++;
            echo "<div class=\"alert alert-info alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Added new User to Database: " . $user_obj->name . "</div>";
        } else {
            echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error: " . $sql . "<br>" . $GLOBALS['conn']->error . "</div>";
        }
    }
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
            <li><a href="#second">Statistics</a></li>
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
                        <li><a href="https://github.com/devinmatte/Git-Challenge" class="button">Learn More</a></li>
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

            <div class="alert alert-success"><h3>Currently each Refresh makes up to <?php echo MAXCALLS; ?> API Calls.
                    Please be patient with Refreshes</h3></div>

            <table class="alt">
                <thead>
                <tr>
                    <th>Rank</th>
                    <th></th>
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
                    echo "<td align=\"center\" width=\"10%\">" . ($row + 1) . "</td>";
                    echo "<td align=\"center\" width=\"10%\">" . "<a href=\"https://github.com/" . $user["username"] . "\"><img src=\"https://avatars1.githubusercontent.com/u/" . $user["id"] . "\" width=\"100%\" alt=\"\" /></a>" . "</td>";
                    echo "<td align=\"center\" width=\"45%\">" . $user["name"] . "</td>";
                    echo "<td align=\"center\" width=\"45%\">" . $user["score"] . "</td>";
                    echo "</tr><tr>";
                    echo "<td colspan=\"4\" nowrap=\"nowrap\" align=\"center\"><div class=\"progress\">
  <div class=\"progress-bar progress-bar-success active fa fa-plus-circle\" title=\"Additions: " . $user["added"] . "\" role=\"progressbar\" style=\"width:" . ((float)((float)$user["added"] / (float)$user["score"])) * 100.0 . "%\">
  </div>
  <div class=\"progress-bar progress-bar-danger active fa fa-minus-circle\" title=\"Deletions: " . $user["removed"] . "\" role=\"progressbar\" style=\"width:" . ((float)((float)$user["removed"] / (float)$user["score"])) * 100.0 . "%\">
  </div>
  <div class=\"progress-bar progress-bar-info active fa fa-upload\" title=\"Commits: " . $user["commits"] . "\" role=\"progressbar\" style=\"width:" . ((float)(((float)$user["commits"] / (float)$user["score"])) * (100.0 * (float)COMMITS)) . "%\">
  </div>
  <div class=\"progress-bar progress-bar-issue active fa fa-exclamation-circle\" title=\"Issues: " . $user["issues"] . "\" role=\"progressbar\" style=\"width:" . ((float)(((float)$user["issues"] / (float)$user["score"])) * (100.0 * (float)ISSUES)) . "%\">
  </div>
  <div class=\"progress-bar progress-bar-pr-merged active fa fa-code-fork\" title=\"Pull Requests (Merged): " . $user["pullRequests"] . "\" role=\"progressbar\" style=\"width:" . ((float)(((float)$user["pullRequests"] / (float)$user["score"])) * (100.0 * (float)PULLREQUESTS)) . "%\">
  </div>
  <div class=\"progress-bar progress-bar-warning active fa fa-trophy\" title=\"Challenge Points: " . $user["challenge"] . "\" role=\"progressbar\" style=\"width:" . ((float)((float)$user["challenge"] / (float)$user["score"])) * 100.0 . "%\">
  </div>
</div>" . "</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
            <footer class="major">
            </footer>
        </section>

        <!-- Second Section -->
        <section id="second" class="main special">
            <header class="major">
                <h2>Statistics</h2>
            </header>
            <ul class="statistics">
                <li class="style2">
                    <span class="icon fa-folder-open-o"></span>
                    <?php
                    $query = "SELECT * FROM Stats ORDER BY commits DESC";
                    $result = $conn->query($query);
                    ?>
                    <strong><?php echo $result->num_rows; ?></strong> Total Repositories
                </li>
                <li class="style3">
                    <span class="icon fa-signal"></span>
                    <?php
                    $query = "SELECT * FROM Stats ORDER BY commits DESC";
                    $result = $conn->query($query);
                    $total = 0;
                    for ($i = 0; $i< $result->num_rows; $i++){
                        $commit = $result->fetch_assoc();
                        $total += $commit["commits"];
                    }
                    ?>
                    <strong><?php echo $total; ?></strong> Total Commits
                </li>
                <li class="style4">
                    <span class="icon fa-users"></span>
                    <?php
                    $query = "SELECT * FROM Users ORDER BY score DESC";
                    $result = $conn->query($query);
                    ?>
                    <strong><?php echo $result->num_rows; ?></strong> Total Contributors
                </li>
            </ul>
            <footer class="major">
            </footer>
        </section>

        <?php
        if (DEBUG == "OFF") {
            echo "<!--";
        }
        ?>

        <section id="debug" class="main special">
            <header class="major">
                <h2>Debugging</h2>
            </header>

            <?php


            $empty = false;
            $page = 0;
            while (!$empty) {
                $page++;
                $url = "https://api.github.com/users/" . GIT_ORG . "/repos" . "?page=" . $page . "&client_id=" . GIT_CLIENT . "&client_secret=" . GIT_SECRET;
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
                $call_count++;
                $empty = empty($obj);

                //Loop through all Reps Issues in Org
                foreach ($obj as &$repo) {
                    $query = "SELECT * FROM Stats WHERE repository='" . $repo->name . "'";
                    $result = $conn->query($query);

                    if ($result->num_rows <= 0) {
                        if ($repo->name != "") {
                            $sql = "INSERT INTO Stats (repository) VALUES ('" . $repo->name . "')";
                            if ($conn->query($sql) === TRUE) {
                                echo "<div class=\"alert alert-info alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Added new Repository to Stats Database: " . $repo->name . "</div>";
                            } else {
                                echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error: " . $sql . "<br>" . $conn->error . "</div>";
                            }
                        }
                    }

                    if ($call_count < (int)MAXCALLS) {
                        echo "<div class=\"alert alert-info alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Checking Repository: " . $repo->name . "</div>";

                        $issue_url = substr($repo->issues_url, 0, -9) . "?state=open&client_id=" . GIT_CLIENT . "&client_secret=" . GIT_SECRET;
                        $issue_json = file_get_contents($issue_url, false, stream_context_create($opts));
                        $issue_obj = json_decode($issue_json);
                        $call_count++;

                        //Loop through all open issues
                        foreach ($issue_obj as &$issue) {
                            $query = "SELECT * FROM Users WHERE id='" . $issue->user->id . "'";
                            $result = $conn->query($query);

                            if (SIGN_UP == "FALSE" && $result->num_rows <= 0) {
                                $user_url = $issue->user->url . "?client_id=" . GIT_CLIENT . "&client_secret=" . GIT_SECRET;
                                $user_json = file_get_contents($user_url, false, stream_context_create($opts));
                                $user_obj = json_decode($user_json);
                                $call_count++;
                                if ($user_obj->name != "") {
                                    $sql = "INSERT INTO Users (name, username, id) VALUES ('" . $user_obj->name . "', '" . $user_obj->login . "', '" . $user_obj->id . "')";
                                    if ($conn->query($sql) === TRUE) {
                                        echo "<div class=\"alert alert-info alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Added new User to Database: " . $user_obj->name . "</div>";
                                    } else {
                                        echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error: " . $sql . "<br>" . $conn->error . "</div>";
                                    }
                                }
                            }

                            if ($result->num_rows > 0) {
                                $user = $result->fetch_assoc();
                                $query = "SELECT issueID FROM Tracked WHERE issueID='" . $issue->id . "'";

                                $result = $conn->query($query);
                                if ($result->num_rows <= 0) {

                                    //Count added stats for each Issue to their corresponding person
                                    $issues = ($user["issues"] + 1);
                                    $sql = "UPDATE Users SET issues=" . $issues . " WHERE id='" . $issue->user->id . "'";
                                    if ($conn->query($sql) === FALSE) {
                                        echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error updating record: " . $conn->error . "</div>";
                                    }

                                    $score = ($user["score"] + (int)ISSUES);
                                    $sql = "UPDATE Users SET score=" . $score . " WHERE id='" . $issue->user->id . "'";
                                    if ($conn->query($sql) === FALSE) {
                                        echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error updating record: " . $conn->error . "</div>";
                                    }

                                    $sql = "INSERT INTO Tracked (issueID) VALUES ('" . $issue->id . "')";
                                    if ($conn->query($sql) === TRUE) {
                                        echo "<div class=\"alert alert-info alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Added a new <i>Open</i> Issue Record to Database: </br>Id: " . $issue->id . "</div>";
                                    } else {
                                        echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error: " . $sql . "<br>" . $conn->error . "</div>";
                                    }
                                }
                            }

                        }

                        $issue_url = substr($repo->issues_url, 0, -9) . "?state=closed&client_id=" . GIT_CLIENT . "&client_secret=" . GIT_SECRET;
                        $issue_json = file_get_contents($issue_url, false, stream_context_create($opts));
                        $issue_obj = json_decode($issue_json);
                        $call_count++;

                        //Loop through all open issues
                        foreach ($issue_obj as &$issue) {
                            $query = "SELECT * FROM Users WHERE id='" . $issue->user->id . "'";
                            $result = $conn->query($query);

                            if (SIGN_UP == "FALSE" && $result->num_rows <= 0) {
                                $user_url = $issue->user->url . "?client_id=" . GIT_CLIENT . "&client_secret=" . GIT_SECRET;
                                $user_json = file_get_contents($user_url, false, stream_context_create($opts));
                                $user_obj = json_decode($user_json);
                                if ($user_obj->name != "") {
                                    $sql = "INSERT INTO Users (name, username, id) VALUES ('" . $user_obj->name . "', '" . $user_obj->login . "', '" . $user_obj->id . "')";
                                    if ($conn->query($sql) === TRUE) {
                                        echo "<div class=\"alert alert-info alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Added new User to Database: " . $user_obj->name . "</div>";
                                    } else {
                                        echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error: " . $sql . "<br>" . $conn->error . "</div>";
                                    }
                                }
                            }

                            if ($result->num_rows > 0) {
                                $user = $result->fetch_assoc();
                                $query = "SELECT issueID FROM Tracked WHERE issueID='" . $issue->id . "'";

                                $result = $conn->query($query);
                                if ($result->num_rows <= 0) {

                                    $merged = null;

                                    if (array_key_exists("pull_request", $issue)) {
                                        $pr_url = $issue->pull_request->url . "?client_id=" . GIT_CLIENT . "&client_secret=" . GIT_SECRET;
                                        $pr_json = file_get_contents($pr_url, false, stream_context_create($opts));
                                        $pr_obj = json_decode($pr_json);
                                        $call_count++;
                                        $merged = $pr_obj->merged_at;
                                    }

                                    if ($merged != "null" && $merged != null && $merged != "") {

                                        //Count added stats for each Issue to their corresponding person
                                        $issues = ($user["pullRequests"] + 1);
                                        $sql = "UPDATE Users SET pullRequests=" . $issues . " WHERE id='" . $issue->user->id . "'";
                                        if ($conn->query($sql) === FALSE) {
                                            echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error updating record: " . $conn->error . "</div>";
                                        }

                                        $score = ($user["score"] + (int)PULLREQUESTS);
                                        $sql = "UPDATE Users SET score=" . $score . " WHERE id='" . $issue->user->id . "'";
                                        if ($conn->query($sql) === FALSE) {
                                            echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error updating record: " . $conn->error . "</div>";
                                        }
                                    } else {
                                        //Count added stats for each Issue to their corresponding person
                                        $issues = ($user["issues"] + 1);
                                        $sql = "UPDATE Users SET issues=" . $issues . " WHERE id='" . $issue->user->id . "'";
                                        if ($conn->query($sql) === FALSE) {
                                            echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error updating record: " . $conn->error . "</div>";
                                        }

                                        $score = ($user["score"] + (int)ISSUES);
                                        $sql = "UPDATE Users SET score=" . $score . " WHERE id='" . $issue->user->id . "'";
                                        if ($conn->query($sql) === FALSE) {
                                            echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error updating record: " . $conn->error . "</div>";
                                        }
                                    }

                                    $sql = "INSERT INTO Tracked (issueID) VALUES ('" . $issue->id . "')";
                                    if ($conn->query($sql) === TRUE) {
                                        echo "<div class=\"alert alert-info alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Added a new <i>Closed</i> Issue/Pull Request Record to Database: </br>Id: " . $issue->id . "</div>";
                                    } else {
                                        echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error: " . $sql . "<br>" . $conn->error . "</div>";
                                    }
                                }
                            }

                        }

                        $repo_empty = false;
                        $repo_page = 0;
                        while (!$repo_empty) {
                            $repo_page++;
                            $repo_url = substr($repo->commits_url, 0, -6) . "?page=" . $repo_page . "&client_id=" . GIT_CLIENT . "&client_secret=" . GIT_SECRET;
                            $repo_json = file_get_contents($repo_url, false, stream_context_create($opts));
                            $repo_obj = json_decode($repo_json);
                            $call_count++;
                            $repo_empty = empty($repo_obj);

                            //Loop through all Commits in each Repo
                            foreach ($repo_obj as &$commit) {
                                if ($call_count < (int)MAXCALLS) {
                                    if (array_key_exists("author", $commit) && !empty($commit->author)) {
                                        $query = "SELECT * FROM Users WHERE id='" . $commit->author->id . "'";
                                        $result = $conn->query($query);

                                        if (SIGN_UP == "FALSE" && $result->num_rows <= 0) {
                                            $user_url = $commit->author->url . "?client_id=" . GIT_CLIENT . "&client_secret=" . GIT_SECRET;
                                            $user_json = file_get_contents($user_url, false, stream_context_create($opts));
                                            $user_obj = json_decode($user_json);
                                            $call_count++;

                                            if ($user_obj->name != "") {
                                                $sql = "INSERT INTO Users (name, username, id) VALUES ('" . $user_obj->name . "', '" . $user_obj->login . "', '" . $user_obj->id . "')";
                                                if ($conn->query($sql) === TRUE) {
                                                    echo "<div class=\"alert alert-info alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Added new User to Database: " . $user_obj->name . "</div>";
                                                } else {
                                                    echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error: " . $sql . "<br>" . $conn->error . "</div>";
                                                }
                                            }
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
                                            $call_count++;

                                            if (array_key_exists("author", $commit) && !empty($commit->author)) {
                                                $query = "SELECT * FROM Users WHERE id='" . $commit->author->id . "'";

                                                $result = $conn->query($query);

                                                if ($result->num_rows > 0) {
                                                    $user = $result->fetch_assoc();

                                                    //Count total stats for each Commit to their corresponding person
                                                    $score = $user["score"] + (($commit_obj->stats->additions * (int)ADDITIONS) + ($commit_obj->stats->deletions * (int)DELETIONS) + ((int)COMMITS));
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

                                                    //Count added stats for each Commit to their corresponding person
                                                    $commits = $user["commits"] + 1;
                                                    $sql = "UPDATE Users SET commits=" . $commits . " WHERE id='" . $commit->author->id . "'";
                                                    if ($conn->query($sql) === FALSE) {
                                                        echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error updating record: " . $conn->error . "</div>";
                                                    }

                                                    $sql = "INSERT INTO Tracked (sha) VALUES ('" . $commit_obj->sha . "')";
                                                    if ($conn->query($sql) === TRUE) {
                                                        $query = "SELECT * FROM Stats WHERE repository='" . $repo->name . "'";
                                                        $result = $conn->query($query);
                                                        if ($result->num_rows > 0) {
                                                            $stats = $result->fetch_assoc();

                                                            $commits = $stats["commits"] + 1;
                                                            $sql = "UPDATE Stats SET commits=" . $commits . " WHERE repository='" . $repo->name . "'";
                                                            if ($conn->query($sql) === FALSE) {
                                                                echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error updating record: " . $conn->error . "</div>";
                                                            }
                                                        }

                                                        echo "<div class=\"alert alert-info alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Added a new Commit Record to Database: </br>Sha: " . $commit_obj->sha . " | Date: " . $commit_obj->commit->committer->date . "</div>";
                                                    } else {
                                                        echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Error: " . $sql . "<br>" . $conn->error . "</div>";
                                                    }

                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        echo "<div class=\"alert alert-success alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Current Call Count after " . $repo->name . ": " . $call_count . "</div>";
                    }
                }
            }

            ?>


            <footer class="major">
            </footer>
        </section>

        <?php
        if (DEBUG == "OFF") {
            echo "-->";
        }
        ?>

    </div>

    <!-- Footer -->
    <footer id="footer">
        <ul class="icons">
            <li><a href="https://github.com/devinmatte/Git-Challenge" class="icon alt fa-github"><span class="label">GitHub</span></a></li>
        </ul>
        <p class="copyright">&copy; Devin Matte. Design: <a href="https://html5up.net">HTML5 UP</a>.</p>
    </footer>

</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
