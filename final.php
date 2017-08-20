<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width">
            <link rel="stylesheet" href="fonts/font-awesome/css/font-awesome.min.css">
            <link rel="stylesheet" type="text/css" href="style.css" />
            <title>Hack::Ardennes - The Git Race</title>
            <link rel="icon" type="image/x-icon" href="favicon.ico" />
            <link rel="icon" type="image/png" href="favicon.png" />
        </head>
        <body>
            <div id="fb-root"></div>
            <script>
                (function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.10";
                  fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
            </script>
            <?php
                include("gitracelib.php");
                //include("config.php");

                $prod = false;
                $branch = "master";
                $deadline = "20170827170000";
                $currentdatetime = date("YmdHis");

                $team = getTeamFromFile("team.csv");

                // Production
                if($prod == true) {
                    if ($currentdatetime < $deadline) {
                        list($commitAll, $commitDay, $commitTeam, $commitHour) = getCommitStatFromGithub($username, $password, $team);
                        setCommitStatToFile($commitAll, $commitDay, $commitTeam, $commitHour);
                    } else {
                        list($commitAll, $commitDay, $commitTeam, $commitHour) = explode(";", getCommitStatFromFile());
                    }
                }
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
                        <?= $commitHour ?><br />
                        <span>Commits/Heure</span>
                    </p>
                </div>
                <div class="gg-stat">
                    <p>
                        <?= $commitDay ?><br />
                        <span>Commits/Jour</span>
                    </p>
                </div>
                <div class="gg-stat">
                    <p>
                        <?= $commitTeam ?><br />
                        <span>Commits/Equipe</span>
                    </p>
                </div>
            </section>
            <section class="gg-social">
                <p>
                    <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://thegitrace.hackardennes.com/" data-text="Course aux commits au #hackathon @CabaretVert ! @github #HackCV17 #DD #OSS" data-via="HackArdennes" data-lang="fr" data-size="large">Tweeter</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                    &nbsp;
                    <div class="fb-share-button" data-href="http://thegitrace.hackardennes.com/" data-layout="button" data-size="large" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fthegitrace.hackardennes.com%2F&amp;src=sdkpreparse">Partager</a></div>
                </p>
            </section>
            <section class="gg-podium">
                <?php
                    // Production
                    if($prod == true) {
                        if ($currentdatetime < $deadline) {
                            $team = getCommitTeamFromGithub($username, $password, $team, $branch);
                            setCommitTeamToFile($team);
                        } else {
                            $team = getTeamFromFile();
                        }
                    }

                    $team = sortArrayByCommitDesc($team);
                    $team2 = array_slice($team, 1, 1);

                    foreach ($team2 as $member) {
                ?>
                <div class="gg-podium-column">
                    <div class="gg-team">
                        <div class="gg-team-score" style="background-color: <?= getCommitColor($member['commit']); ?>">
                            <p><span><?= $member['commit']; ?></span><br />commits</p>
                        </div>
                        <div class="gg-team-name" style="background-color: <?= getCommitColor($member['commit']); ?>">
                            <p><?= $member['name']; ?></p>
                        </div>
                    </div>
                    <div class="gg-podium-step"></div>
                </div>
                <?php
                    }
                    
                    $team = sortArrayByCommitDesc($team);
                    $team1 = array_slice($team, 0, 1);

                    foreach ($team1 as $member) {
                ?>
                <div class="gg-podium-column">
                    <div class="gg-team">
                        <div class="gg-team-score" style="background-color: <?= getCommitColor($member['commit']); ?>">
                            <p><span><?= $member['commit']; ?></span><br />commits</p>
                        </div>
                        <div class="gg-team-name" style="background-color: <?= getCommitColor($member['commit']); ?>">
                            <p><?= $member['name']; ?></p>
                        </div>
                    </div>
                    <div class="gg-podium-step"></div>
                </div>
                <?php
                    }
                    
                    $team = sortArrayByCommitDesc($team);
                    $team3 = array_slice($team, 2, 1);

                    foreach ($team3 as $member) {
                ?>
                <div class="gg-podium-column">
                    <div class="gg-team">
                        <div class="gg-team-score" style="background-color: <?= getCommitColor($member['commit']); ?>">
                            <p><span><?= $member['commit']; ?></span><br />commits</p>
                        </div>
                        <div class="gg-team-name" style="background-color: <?= getCommitColor($member['commit']); ?>">
                            <p><?= $member['name']; ?></p>
                        </div>
                    </div>
                    <div class="gg-podium-step"></div>
                </div>
                <?php
                    }
                ?>
            </section>
            <section class="gg-ranking">
                <?php
                    foreach ($team as $member) {
                ?>
                <div class="gg-team">
                    <div class="gg-team-score" style="background-color: <?= getCommitColor($member['commit']); ?>">
                        <p><span><?= $member['commit']; ?></span><br />commits</p>
                    </div>
                    <div class="gg-team-name" style="background-color: <?= getCommitColor($member['commit']); ?>">
                        <p><?= $member['name']; ?></p>
                    </div>
                </div>
                <?php
                    }
                ?>
            </section>
        </body>
    </html>
