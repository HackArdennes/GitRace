<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8" />
            <link rel="stylesheet" href="fonts/font-awesome/css/font-awesome.min.css">
            <link rel="stylesheet" type="text/css" href="style.css" />
            <title>Hack::Ardennes - GIT GAME</title>
        </head>
        <body>
            <header>
                <div class="gg-logo"></div>
                <div class="gg-title"></div>
            </header>
            <section class="gg-statistics">
                <div class="gg-stat">
                    <p>
                        366<br />
                        <span>Commits</span>
                    </p>
                </div>
                <div class="gg-stat">
                    <p>
                        5<br />
                        <span>commits/heure</span>
                    </p>
                </div>
                <div class="gg-stat">
                    <p>
                        122<br />
                        <span>commits/jour</span>
                    </p>
                </div>
                <div class="gg-stat">
                    <p>
                        61<br />
                        <span>commits/équipe</span>
                    </p>
                </div>
            </section>
            <section class="gg-race">
                <?php
                    include("gitgamelib.php");
                    $team = createTeamFromCSV("team.csv");

                    //$team = setCommitTeamFromGithub("Nekrofage", $team); // Uncomment for production
                    
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
