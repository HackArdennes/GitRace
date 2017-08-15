<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8" />
            <link rel="stylesheet" href="fonts/font-awesome/css/font-awesome.min.css">
            <link rel="stylesheet" type="text/css" href="style.css" />
            <title>Hack::Ardennes - The Git Race</title>
        </head>
        <body>
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.10";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
            <?php
                include("gitracelib.php");
                include("config.php");

                $prod = true;
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
            <section>
                <div class="gg-social">
                    <p>
                        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://thegitrace.hackardennes.com/" data-text="Course aux commits au #hackathon @CabaretVert ! @github #HackCV17 #DD #OSS" data-via="HackArdennes" data-lang="fr" data-size="large">Tweeter</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                        &nbsp;
                        <div class="fb-share-button" data-href="http://thegitrace.hackardennes.com/" data-layout="button" data-size="large" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fthegitrace.hackardennes.com%2F&amp;src=sdkpreparse">Partager</a></div>
                    </p>
                </div>

            </section>
            <section class="gg-race">
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

                    $team = sortArrayByName($team);

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
                    $message = "#Hackardennes #Hackathon2018 #CabaretVert #GitRace";
                    //sendTweet($message);
                ?>
            </section>
            <!--<section class="gg-mountain"></section>-->
        </body>
    </html>
