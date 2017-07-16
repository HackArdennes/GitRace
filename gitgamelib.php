<?php
/*
    Git game library v0.0.1
*/

/*
    Create teams from csv file
*/

function createTeamFromCSV($teamCSV) {
    
    $team = array();

    if (($handle = fopen($teamCSV, "r")) !== FALSE) {
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
        usort($team, function($a, $b) {
            return strcmp($a["name"], $b["name"]);
        });

        return $array;
}


/*
    Get the commit number by username, repository and branch
*/

function getGithubCommit($username, $repository, $branch) {
    require_once 'vendor/autoload.php';

    $client = new \Github\Client();

    $commits = $client->api('repo')->commits()->all($username, $repository, array('sha' => $branch));

    return count($commits);
}


/*
    Display commit number for each team
*/

function displayCommitTeam($username, $branch, $team) {
    foreach ($team as $member) {
        echo $member['name'] . " " . $member['project'] . " " . getGithubCommit($username, $member['project'], $branch) . "\n";
        echo "--------------------\n";
    }
 
}


/*
    Set commit number from the Github into team array
*/

function setCommitTeamFromGithub($username, $branch, $team) {
    $teamTmp = array();

    foreach ($team as $member) {
        $commit = getGithubCommit($username, $member['project'], $branch);
        
        $memberTmp = array (
                "name" => $member['name'],
                "project" => $member['project'],
                "commit" => $commit);

        array_push($teamTmp, $memberTmp);
    }
    return $teamTmp;
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

function getCommitStat($username, $team) {
    require_once 'vendor/autoload.php';

    $client = new \Github\Client();

    $today = date("Y-m-d");


    $commitAll = 0;
    $commitDay = 0;


    foreach ($team as $member) {
        $commits = $client->api('repo')->commits()->all($username, $member['project'], array('sha' => "master"));
        foreach($commits as $commit) {
            // All
            $commitAll++;

            // Day
            if (substr($commit["commit"]["committer"]["date"], 0, 10) == $today) {
                $commitDay++;
            }
        }
    }

    $commitTeam = ceil($commitAll / count($team));

    return array($commitAll, $commitDay, $commitTeam);
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
