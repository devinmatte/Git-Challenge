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
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $score = ($user["added"] * $configs->points->additions) + ($user["removed"] * $configs->points->deletions) + ($user["challenge"] * $configs->points->challenges) + ($user["commits"] * $configs->points->commits) + ($user["issues"] * $configs->points->issues) + ($user["pullRequests"] * $configs->points->pullRequests);
                    $sql = "UPDATE Users SET score=" . $score . " WHERE id='" . $user["id"] . "'";
                    $conn->query($sql);
                    echo "<tr>";
                    echo "<td>" . ($row + 1) . "</td>";
                    echo "<td>" . $user["name"] . "</td>";
                    echo "<td>" . $score . "</td>";
                    echo "</tr>";
                }
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

            <div class="alert alert-success"><h3>Point Scaling</h3><h4>Additions: <?php echo $configs->points->additions;?> | Deletions: <?php echo $configs->points->deletions;?> | Commits: <?php echo $configs->points->commits;?> | Issues: <?php echo $configs->points->issues;?> | Merged Pull Requests: <?php echo $configs->points->pullRequests;?></h4></div>

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
                    $score = ($user["added"] * $configs->points->additions) + ($user["removed"] * $configs->points->deletions) + ($user["challenge"] * $configs->points->challenges) + ($user["commits"] * $configs->points->commits) + ($user["issues"] * $configs->points->issues) + ($user["pullRequests"] * $configs->points->pullRequests);
                    $sql = "UPDATE Users SET score=" . $score . " WHERE id='" . $user["id"] . "'";
                    $conn->query($sql);

                    echo "<tr>";
                    echo "<td align=\"center\" width=\"10%\">" . ($row + 1) . "</td>";
                    echo "<td align=\"center\" width=\"10%\">" . "<a href=\"https://github.com/" . $user["username"] . "\"><img src=\"https://avatars1.githubusercontent.com/u/" . $user["id"] . "\" width=\"100%\" alt=\"\" /></a>" . "</td>";
                    echo "<td align=\"center\" width=\"45%\">" . $user["name"] . "</td>";
                    echo "<td align=\"center\" width=\"45%\">" . $score . "</td>";
                    echo "</tr><tr>";
                    echo "<td colspan=\"4\" nowrap=\"nowrap\" align=\"center\"><div class=\"progress\">
  <div class=\"progress-bar progress-bar-success active fa fa-plus-circle\" title=\"Additions: " . $user["added"] . "\" role=\"progressbar\" style=\"width:" . ((float)((float)$user["added"] / (float)$score)) * (100.0 * $configs->points->additions) . "%\">
  </div>
  <div class=\"progress-bar progress-bar-danger active fa fa-minus-circle\" title=\"Deletions: " . $user["removed"] . "\" role=\"progressbar\" style=\"width:" . ((float)((float)$user["removed"] / (float)$score)) * (100.0 * $configs->points->deletions) . "%\">
  </div>
  <div class=\"progress-bar progress-bar-info active fa fa-upload\" title=\"Commits: " . $user["commits"] . "\" role=\"progressbar\" style=\"width:" . ((float)(((float)$user["commits"] / (float)$score)) * (100.0 * $configs->points->commits)) . "%\">
  </div>
  <div class=\"progress-bar progress-bar-issue active fa fa-exclamation-circle\" title=\"Issues: " . $user["issues"] . "\" role=\"progressbar\" style=\"width:" . ((float)(((float)$user["issues"] / (float)$score)) * (100.0 * $configs->points->issues)) . "%\">
  </div>
  <div class=\"progress-bar progress-bar-pr-merged active fa fa-code-fork\" title=\"Pull Requests (Merged): " . $user["pullRequests"] . "\" role=\"progressbar\" style=\"width:" . ((float)(((float)$user["pullRequests"] / (float)$score)) * (100.0 * $configs->points->pullRequests)) . "%\">
  </div>
  <div class=\"progress-bar progress-bar-warning active fa fa-trophy\" title=\"Challenge Points: " . $user["challenge"] . "\" role=\"progressbar\" style=\"width:" . ((float)((float)$user["challenge"] / (float)$score)) * (100.0 * $configs->points->challenges) . "%\">
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
                    for ($i = 0; $i < $result->num_rows; $i++) {
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
