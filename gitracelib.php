<?php
/*
    Git game library v0.0.1
*/

/*
    Create teams from csv file
*/

function getTeamFromFile() {
    $file = "team.csv";
    $team = array();

    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            $member = array(
                    'name' => $data[0],
                    'project' => $data[1],
                    'commit' => $data[2],
                );

            array_push($team, $member);        
        }
        fclose($handle);
    }

    return $team;
}

/*
    Display team array in debug mode
*/

function displayDebugTeam($team) {
    foreach ($team as $member) {
        foreach($member as $key => $value) {
            echo $key . " => " . $value . "\n";
        }
        echo "--------------------\n";
    }
}


/*
    Sort the team array by commit number
*/

function sortArrayByCommit($array) {
        // Sort multiarray by interger
        usort($array, function($a, $b) {
            return $a['commit'] - $b['commit'];
        });

        return $array;
}


/*
    Sort the team array by name team
*/

function sortArrayByName($array) {
        usort($array, function($a, $b) {
            return strcmp($a["name"], $b["name"]);
        });

        return $array;
}


/*
    Get the commit number by username, repository and branch
*/

function getGithubCommit($usernameOrToken, $password, $repository, $branch) {
    require_once 'vendor/autoload.php';

    $client = new \Github\Client();
    $client->authenticate($usernameOrToken, $password, Github\Client::AUTH_HTTP_PASSWORD);

    $commits = $client->api('repo')->commits()->all($usernameOrToken, $repository, array('sha' => $branch));

    return count($commits);
}


/*
    Display commit number for each team
*/

function displayCommitTeam($username, $password, $branch, $team) {
    foreach ($team as $member) {
        echo $member['name'] . " " . $member['project'] . " " . getGithubCommit($username, $password, $member['project'], $branch) . "\n";
        echo "--------------------\n";
    }
 
}


/*
    Get commit number from the Github into team array
*/

function getCommitTeamFromGithub($username, $password, $team, $branch) {
    $teamTmp = array();

    foreach ($team as $member) {
        $commit = getGithubCommit($username, $password, $member['project'], $branch);
        
        $memberTmp = array (
                "name" => $member['name'],
                "project" => $member['project'],
                "commit" => $commit);

        array_push($teamTmp, $memberTmp);
    }


    return $teamTmp;
}


function setCommitTeamToFile($team){
    $file = "team.csv";
    unlink($file);
    foreach ($team as $member) {
        $line = $member['name'] . ";" . $member['project'] . ";" . $member['commit'] . "\r\n";
        file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
    }
    
}


/*
    Find the minimum and the maximum commit number
*/

function findMinMaxCommit($team) {
    $min = null;
    $max = null;

    foreach ($team as $member) {
        $minmax[] = $member['commit']; 
    }

    $min = min($minmax);
    $max = max($minmax);

    return array($min, $max);
}


/*
    Get commit statistic
*/

function getCommitStatFromGithub($usernameOrToken, $password, $team) {
    require_once 'vendor/autoload.php';

    $client = new \Github\Client();
    $client->authenticate($usernameOrToken, $password, Github\Client::AUTH_HTTP_PASSWORD);

    $today = date("Y-m-d");

    $commitArr = array_fill(0, 24, 0);
    $commitHour = 0;
    $commitAll = 0;
    $commitDay = 0;

    foreach ($team as $member) {
        $commits = $client->api('repo')->commits()->all($usernameOrToken, $member['project'], array('sha' => "master"));
        foreach($commits as $commit) {
            // Hour
            $commitArr[intval(substr($commit["commit"]["committer"]["date"], 11, 2))] += 1;

            // All
            $commitAll++;

            // Day
            if (substr($commit["commit"]["committer"]["date"], 0, 10) == $today) {
                $commitDay++;
            }
        }
    }

    // Team
    $commitTeam = ceil($commitAll / count($team));

    // Hour
    $commitHour = ceil($commitAll / count(array_filter($commitArr)));

    return array($commitAll, $commitDay, $commitTeam, $commitHour);
}


/*

*/

function getCommitStatFromFile() {
    $file = "commitstat.csv";
    $f = fopen($file, 'r');
    $line = fgets($f);
    fclose($f);
    return $line;
}


/*

*/

function setCommitStatToFile($commitAll, $commitDay, $commitTeam, $commitHour) {
    $file = "commitstat.csv";
    file_put_contents($file, "$commitAll;$commitDay;$commitTeam;$commitHour");
}


/*
    Compute the race
*/

function computeRace($team) {
    list($min, $max) = findMinMaxCommit($team);

    foreach ($team as $member) {
        // Compute length of the race
        $length = $member['commit'] / $max * 100;

        echo $member['name'] . " " . $member['project'] . " => " . $member['commit'] . " == " . $length;
        echo "\n";
        echo "--------------------\n";
    }
}

?>
