<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8" />
            <link rel="stylesheet" href="fonts/font-awesome/css/font-awesome.min.css">
            <link rel="stylesheet" type="text/css" href="style.css" />
            <title>Hack::Ardennes - GIT GAME</title>
        </head>
        <body>
            <?php
                include("gitgamelib.php");
                $team = createTeamFromCSV("team.csv");

                $username = "Nekrofage";
                $today = date("Y-m-d");

                list($commitAll, $commitDay) = getCommitStat($username, $team);
            ?>
            <header>
                <div class="gg-logo"></div>
                <div class="gg-title"></div>
            </header>
            <section class="gg-statistics">
                <div class="gg-stat">
                    <p>
                        <?= $commitAll ?><br />
                        <span>Commits</span>
                    </p>
                </div>
                <div class="gg-stat">
                    <p>
                        XXX<br />
                        <span>commits/heure</span>
                    </p>
                </div>
                <div class="gg-stat">
                    <p>
                        <?= $commitDay ?><br />
                        <span>commits/jour</span>
                    </p>
                </div>
                <div class="gg-stat">
                    <p>
                        XXX<br />
                        <span>commits/équipe</span>
                    </p>
                </div>
            </section>
            <section class="gg-race">
                <?php
                    // Uncomment the following line for production
                    //$team = setCommitTeamFromGithub($username, $team);
                    
                    list($min, $max) = findMinMaxCommit($team);

                    foreach ($team as $member) {
                        $length = $member['commit'] / $max * 100;
                ?>
                    <div class="gg-racer" style="width: <?= $length ?>%">
                        <div class="gg-racer-info">
                            <p>
                                <?= $member['name'] ?><br />
                                <span><?= $member['commit'] ?> commits</span>
                            </p>
                        </div>
                    </div>
                <?php
                    }  
                ?>
            </section>
            <!--<section class="gg-mountain"></section>-->
        </body>
    </html>
