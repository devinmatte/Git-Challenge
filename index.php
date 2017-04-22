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
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="assets/css/ie9.css"/><![endif]-->
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="assets/css/ie8.css"/><![endif]-->
</head>

<?php
if (file_exists("include/configuration.php")) {
    $configs = include("include/configuration.php");
} else {
    $configs = include("include/configuration-template.php");
}
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

            for ($row = 0; $row < $result->num_rows; $row++) {
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $challengeScore = 0;

                    $ChallengeQuery = "SELECT * FROM Challenges ORDER BY points DESC";
                    $challengeResult = $conn->query($ChallengeQuery);

                    for ($challengeRow = 0; $challengeRow < $challengeResult->num_rows; $challengeRow++) {
                        $challenge = $challengeResult->fetch_assoc();
                        if (strpos($challenge["users"], $user["username"]) !== false) {
                            $challengeScore += $challenge["points"];
                        }
                    }
                    $sql = "UPDATE Users SET challenge=" . $challengeScore . " WHERE id='" . $user["id"] . "'";
                    $conn->query($sql);
                }
            }

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
            <?php
            if ($configs->options->info == true) {
                echo "<li><a href=\"#intro\" class=\"active\">Introduction</a></li>";
            }
            ?>
            <li><a href="#breakdown">Point Breakdown</a></li>
            <?php
            if ($configs->options->event == true) {
                echo "<li><a href=\"#event\">Event</a></li>";
            }
            ?>
            <?php
            if ($configs->options->challenges == true) {
                echo "<li><a href=\"#challenges\">Challenges</a></li>";
            }
            ?>
            <li><a href="#second">Statistics</a></li>
            <?php
            if ($configs->options->debug == true) {
                echo "<li><a href=\"#debug\">Debug</a></li>";
            }
            ?>
        </ul>
    </nav>

    <!-- Main -->
    <div id="main">

        <?php
        if ($configs->options->info == false) {
            echo "<!--";
        }
        ?>

        <!-- Introduction -->
        <section id="intro" class="main">
            <div class="spotlight">
                <div class="content">
                    <header class="major">
                        <h2>Idea</h2>
                    </header>
                    <p>Git Challenge was a project I had an idea for when I looked over a GitHub Organisation I was a
                        part of. It is for my old High School Technology Team, the organisation that taught me most of
                        what I knew about programming before I came to RIT. The projects in the GitHub hadn't been
                        touched
                        by anyone except myself and a few other Team Alumni. So I thought I should come up with a way to
                        encourage contributing to these projects, and to teach people git. So I came up with
                        Git-Challenge. A app made to gamify contributing to projects, for any Organisation. Not just
                        this Tech Team. It could be used for CSH, or really any other git organisation with multiple
                        contributors.</p>
                    <ul class="actions">
                        <li><a href="https://github.com/devinmatte/Git-Challenge" class="button">Learn More</a></li>
                        <li><a href="howTogit.php" class="button">Learn Git</a></li>
                    </ul>
                </div>
            </div>
        </section>

        <?php
        if ($configs->options->info == false) {
            echo "-->";
        }
        ?>

        <!-- Breakdown Section -->
        <section id="breakdown" class="main special">
            <header class="major">
                <h2>Point Breakdown</h2>
            </header>

            <div class="alert alert-success"><h3>Point Scaling</h3><h4>
                    Additions: <?php echo $configs->points->additions; ?> |
                    Deletions: <?php echo $configs->points->deletions; ?> |
                    Commits: <?php echo $configs->points->commits; ?> | Issues: <?php echo $configs->points->issues; ?>
                    | Merged Pull Requests: <?php echo $configs->points->pullRequests; ?></h4></div>

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
                    if ($result->num_rows > 0) {
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
                }
                ?>
                </tbody>
            </table>
            <footer class="major">
                <a class="button special fit"
                   title="<?php echo $configs->options->maxcalls; ?> API Calls | Will take time, please be patient"
                   href='index.php?load=true'>Load New Data</a>
            </footer>
        </section>

        <?php
        if ($configs->options->event == true) {
            ?>

            <section id="event" class="main special">
                <header class="major">
                    <h2>Event</h2>
                </header>
                <p>
                    <?php
                    echo "Work in Progress. Cool prizes coming soon!";
                    ?>
                </p>
                <footer class="major">
                </footer>
            </section>

            <?php
        }
        ?>


        <?php
        if ($configs->options->challenges == true) {
            ?>

            <section id="challenges" class="main special">
                <header class="major">
                    <h2>Challenges</h2>
                </header>
                <?php

                $query = "SELECT * FROM Challenges ORDER BY name";
                $result = $conn->query($query);

                $styles = ["style5", "style1", "style2", "style3", "style4"];
                $stylePointer = 0;

                for ($row = 0; $row < $result->num_rows; $row++) {
                    $challenge = $result->fetch_assoc();
                    ?>

                    <div id="testmodal<?php echo $challenge['id'] ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"><?php echo $challenge['name'] ?></h4>
                                    <h5 class="modal-title"><?php echo $challenge['points'] ?> Points</h5>
                                </div>
                                <div class="modal-body">
                                    <?php echo $challenge['description'] ?>
                                    <form action="<?php echo $challenge['hint'] ?>">
                                        <input type="submit" class="button special small fit" value="Hint"/>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="button fit" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <?php
                }

                $query = "SELECT * FROM Challenges ORDER BY name";
                $result = $conn->query($query);

                echo "<ul class=\"statistics\">";
                $styles = ["style5", "style1", "style2", "style3", "style4"];
                $stylePointer = 0;
                for ($row = 0; $row < $result->num_rows; $row++) {
                    $challenge = $result->fetch_assoc();
                    ?>

                    <?php if ($stylePointer >= 5) {
                        echo "</ul><ul class=\"statistics\">";
                    } ?>
                    <li data-toggle="modal" data-target="#testmodal<?php echo $challenge['id'] ?>"
                        class="<?php if ($stylePointer < 5) {
                            echo $styles[$stylePointer++];
                        } else {
                            $stylePointer = 0;
                            echo $styles[$stylePointer++];
                        } ?> ">
                        <span class="icon fa-trophy"></span>
                        <p><?php echo $challenge['name'] ?></p>
                    </li>
                    <?php
                }
                ?>
                </ul>
                <footer class="major">
                </footer>
            </section>
            <?php
        }
        ?>

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

            if (isset($_GET['load'])) {
                require("main.php");
                $main = new main($conn, $configs, $alert);
            }

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
        <p class="copyright">
            <a href="https://github.com/devinmatte/Git-Challenge" class="icon fa-github"><span
                        class="label">GitHub</span></a>
            </br>
            &copy; <?php echo date("Y") ?> <a href="https://github.com/devinmatte">Devin Matte</a> | Design: <a
                    href="https://html5up.net">HTML5 UP</a>.</p>
    </footer>

</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
