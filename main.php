<?php

global $call_count;

class main
{
    public function __construct(mysqli $conn, $configs, Alert $alert)
    {
        $GLOBALS['call_count'] = 0;

        $empty = false;
        $page = 0;
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: PHP'
                ]
            ]
        ];
        while (!$empty) {
            $page++;
            $url = "https://api.github.com/users/" . $configs->git->org . "/repos" . "?page=" . $page . "&client_id=" . $configs->git->client . "&client_secret=" . $configs->git->secret;

            $json = file_get_contents($url, false, stream_context_create($opts));
            $obj = json_decode($json);
            $GLOBALS['call_count']++;
            $empty = empty($obj);

            //Loop through all Reps Issues in Org
            foreach ($obj as &$repo) {
                $this->repo($conn, $configs, $alert, $opts, $obj, $repo);
            }
        }
    }

    public function addUser(mysqli $conn, $configs, Alert $alert, $opts, $url, $login)
    {
        if (!array_key_exists($login, $configs->blacklist)) {
            $user_url = $url . "?client_id=" . $configs->git->client . "&client_secret=" . $configs->git->secret;
            $user_json = file_get_contents($user_url, false, stream_context_create($opts));
            $user_obj = json_decode($user_json);
            $GLOBALS['call_count']++;
            if ($user_obj->name != "" && !array_key_exists($user_obj->login, $configs->blacklist)) {
                $sql = "INSERT INTO Users (name, username, id) VALUES ('" . $user_obj->name . "', '" . $user_obj->login . "', '" . $user_obj->id . "')";
                if ($conn->query($sql) === TRUE) {
                    $message = "Added a new User to Database: " . $user_obj->name;
                    $alert->info($message);
                } else {
                    $message = "Error: " . $sql . "\n" . $conn->error;
                    $alert->warning($message);
                }
            }
        }
    }

    public function openIssue(mysqli $conn, $configs, Alert $alert, $opts, $repo, $issue)
    {
        $query = "SELECT * FROM Users WHERE id='" . $issue->user->id . "'";
        $result = $conn->query($query);

        if ($configs->options->pool == true && $result->num_rows <= 0 && ($repo->fork != true && $repo->fork != "true") && !array_key_exists($issue->user->login, $configs->blacklist)) {
            $this->addUser($conn, $configs, $alert, $opts, $issue->user->url, $issue->user->login);
        }

        if ($result->num_rows > 0 && !array_key_exists("pull_request", $issue) && !array_key_exists($issue->user->login, $configs->blacklist)) {
            $user = $result->fetch_assoc();
            $query = "SELECT issueID FROM Tracked WHERE issueID='" . $issue->id . "'";

            $result = $conn->query($query);
            if ($result->num_rows <= 0 && !array_key_exists($issue->user->login, $configs->blacklist)) {

                //Count added stats for each Issue to their corresponding person
                $issues = ($user["issues"] + 1);
                $sql = "UPDATE Users SET issues=" . $issues . " WHERE id='" . $issue->user->id . "'";
                if ($conn->query($sql) === FALSE) {
                    $message = "Error updating record: " . $conn->error;
                    $alert->warning($message);
                }

                $sql = "INSERT INTO Tracked (issueID) VALUES ('" . $issue->id . "')";
                if ($conn->query($sql) === TRUE) {
                    echo "<div class=\"alert alert-info alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>Added a new <i>Open</i> Issue Record to Database: </br>Id: " . $issue->id . "</div>";
                } else {
                    $message = "Error: " . $sql . "\n" . $conn->error;
                    $alert->warning($message);
                }
            }
        }
    }

    public function closedIssue(mysqli $conn, $configs, Alert $alert, $opts, $repo, $issue)
    {
        $query = "SELECT * FROM Users WHERE id='" . $issue->user->id . "'";
        $result = $conn->query($query);

        if ($configs->options->pool == true && $result->num_rows <= 0 && ($repo->fork != true && $repo->fork != "true") && !array_key_exists($issue->user->login, $configs->blacklist)) {
            $this->addUser($conn, $configs, $alert, $opts, $issue->user->url, $issue->user->login);
        }

        if ($result->num_rows > 0 && !array_key_exists("message", $issue) && !array_key_exists($issue->user->login, $configs->blacklist)) {
            $user = $result->fetch_assoc();
            $query = "SELECT issueID FROM Tracked WHERE issueID='" . $issue->id . "'";

            $result = $conn->query($query);
            if ($result->num_rows <= 0) {

                $merged = null;

                if (array_key_exists("pull_request", $issue)) {
                    $pr_url = $issue->pull_request->url . "?client_id=" . $configs->git->client . "&client_secret=" . $configs->git->secret;
                    $pr_json = file_get_contents($pr_url, false, stream_context_create($opts));
                    $pr_obj = json_decode($pr_json);
                    $GLOBALS['call_count']++;
                    $merged = $pr_obj->merged_at;
                }

                if ($merged != "null" && $merged != null && $merged != "") {

                    //Count added stats for each Issue to their corresponding person
                    $issues = ($user["pullRequests"] + 1);
                    $sql = "UPDATE Users SET pullRequests=" . $issues . " WHERE id='" . $issue->user->id . "'";
                    if ($conn->query($sql) === FALSE) {
                        $message = "Error updating record: " . $conn->error;
                        $alert->warning($message);
                    }
                } else {
                    //Count added stats for each Issue to their corresponding person
                    $issues = ($user["issues"] + 1);
                    $sql = "UPDATE Users SET issues=" . $issues . " WHERE id='" . $issue->user->id . "'";
                    if ($conn->query($sql) === FALSE) {
                        $message = "Error updating record: " . $conn->error;
                        $alert->warning($message);
                    }

                }

                $sql = "INSERT INTO Tracked (issueID) VALUES ('" . $issue->id . "')";
                if ($conn->query($sql) === TRUE) {
                    $message = "Added a new Closed Issue/Pull Request Record to Database: \nId: " . $issue->id;
                    $alert->info($message);
                } else {
                    $message = "Error: " . $sql . "\n" . $conn->error;
                    $alert->warning($message);
                }
            }
        }
    }

    public function commit(mysqli $conn, $configs, Alert $alert, $opts, $repo, $commit, $repo_empty)
    {
        if ($GLOBALS['call_count'] < $configs->options->maxcalls && !$repo_empty && !array_key_exists("message", $commit)) {
            if (array_key_exists("author", $commit) && !empty($commit->author)) {
                $query = "SELECT * FROM Users WHERE id='" . $commit->author->id . "'";
                $result = $conn->query($query);

                if ($configs->options->pool == true && $result->num_rows <= 0 && ($repo->fork != true && $repo->fork != "true") && !array_key_exists($commit->author->login, $configs->blacklist)) {
                    $this->addUser($conn, $configs, $alert, $opts, $commit->author->url, $commit->author->login);
                }

                if ($result->num_rows > 0 && !array_key_exists($commit->author->login, $configs->blacklist)) {

                    $query = "SELECT sha FROM Tracked WHERE sha='" . $commit->sha . "'";

                    $result = $conn->query($query);
                    if ($result->num_rows <= 0) {
                        //Getting Proper Results
                        $commit_url = $commit->url . "?client_id=" . $configs->git->client . "&client_secret=" . $configs->git->secret;
                        $commit_json = file_get_contents($commit_url, false, stream_context_create($opts));
                        $commit_obj = json_decode($commit_json);
                        $GLOBALS['call_count']++;

                        if (array_key_exists("author", $commit) && !empty($commit->author)) {
                            $query = "SELECT * FROM Users WHERE id='" . $commit->author->id . "'";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                $user = $result->fetch_assoc();

                                //Count added stats for each Commit to their corresponding person
                                $added = $user["added"] + $commit_obj->stats->additions;
                                $sql = "UPDATE Users SET added=" . $added . " WHERE id='" . $commit->author->id . "'";
                                if ($conn->query($sql) === FALSE) {
                                    $message = "Error updating record: " . $conn->error;
                                    $alert->warning($message);
                                }

                                //Count removed stats for each Commit to their corresponding person
                                $removed = $user["removed"] + $commit_obj->stats->deletions;
                                $sql = "UPDATE Users SET removed=" . $removed . " WHERE id='" . $commit->author->id . "'";
                                if ($conn->query($sql) === FALSE) {
                                    $message = "Error updating record: " . $conn->error;
                                    $alert->warning($message);
                                }

                                //Count added stats for each Commit to their corresponding person
                                $commits = $user["commits"] + 1;
                                $sql = "UPDATE Users SET commits=" . $commits . " WHERE id='" . $commit->author->id . "'";
                                if ($conn->query($sql) === FALSE) {
                                    $message = "Error updating record: " . $conn->error;
                                    $alert->warning($message);
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
                                            $message = "Error updating record: " . $conn->error;
                                            $alert->warning($message);
                                        }
                                    }
                                    $message = "Added a new Commit Record to Database:\nSha: " . $commit_obj->sha . " | Date: " . $commit_obj->commit->committer->date;
                                    $alert->info($message);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function repo(mysqli $conn, $configs, Alert $alert, $opts, $obj, $repo)
    {
        if (!array_key_exists("message", $obj)) {
            $query = "SELECT * FROM Stats WHERE repository='" . $repo->name . "'";
            $result = $conn->query($query);

            if ($result->num_rows <= 0 && !array_key_exists("message", $obj)) {
                if ($repo->name != "") {
                    $sql = "INSERT INTO Stats (repository) VALUES ('" . $repo->name . "')";
                    if ($conn->query($sql) === TRUE) {
                        $message = "Added a new Repository to Stats Database: " . $repo->name;
                        $alert->info($message);
                    }
                }
            }

            if ($GLOBALS['call_count'] < $configs->options->maxcalls && !array_key_exists("message", $obj)) {
                $message = "Checking Repository: " . $repo->name;
                $alert->info($message);

                $issue_url = substr($repo->issues_url, 0, -9) . "?state=open&client_id=" . $configs->git->client . "&client_secret=" . $configs->git->secret;
                $issue_json = file_get_contents($issue_url, false, stream_context_create($opts));
                $issue_obj = json_decode($issue_json);
                $GLOBALS['call_count']++;

                //Loop through all open issues
                foreach ($issue_obj as &$issue) {
                    $this->openIssue($conn, $configs, $alert, $opts, $repo, $issue);
                }

                $issue_url = substr($repo->issues_url, 0, -9) . "?state=closed&client_id=" . $configs->git->client . "&client_secret=" . $configs->git->secret;
                $issue_json = file_get_contents($issue_url, false, stream_context_create($opts));
                $issue_obj = json_decode($issue_json);
                $GLOBALS['call_count']++;

                //Loop through all closed issues
                foreach ($issue_obj as &$issue) {
                    $this->closedIssue($conn, $configs, $alert, $opts, $repo, $issue);
                }

                $repo_empty = false;
                $repo_page = 0;
                while (!$repo_empty) {
                    $repo_page++;
                    $repo_url = substr($repo->commits_url, 0, -6) . "?page=" . $repo_page . "&client_id=" . $configs->git->client . "&client_secret=" . $configs->git->secret;
                    $repo_json = file_get_contents($repo_url, false, stream_context_create($opts));
                    $repo_obj = json_decode($repo_json);
                    $GLOBALS['call_count']++;
                    $repo_empty = empty($repo_obj);

                    //Loop through all Commits in each Repo
                    foreach ($repo_obj as &$commit) {
                        $this->commit($conn, $configs, $alert, $opts, $repo, $commit, $repo_empty);
                    }
                }
                $message = "Current Call Count after " . $repo->name . ": " . $GLOBALS['call_count'];
                $alert->success($message);
            }
        }
    }

}
