<?
if (isset($_GET['phase'])) {
    $phaseID = $_GET['phase'];
} else {
    $phaseID = PHASE_ID_ACTIVE;
}
$phase = $engine->getPhase($phaseID);
if ($phase['phasePrecedente'] != NULL) {
    $phases = $engine->getPhaseByPhaseRoot($phase['phasePrecedente']);
} else {
    $phases = array($phase);
}
?><script type="text/javascript">
<!--
    function changePhase(phaseID) {
        window.location.href = "?op=view_results&phase="+phaseID;
    }
    
    function toggle_exact_bets(id) {
      var exact_bets = document.getElementById('exact_bets_'+id);
      if(exact_bets.style.display == 'inline') exact_bets.style.display = 'none';
      else exact_bets.style.display = 'inline';
    }

    function toggle_good_bets(id) {
      var good_bets = document.getElementById('good_bets_'+id);
      if(good_bets.style.display == 'inline') good_bets.style.display = 'none';
      else good_bets.style.display = 'inline';
    }
//-->
</script>
<div class="maincontent">
    <div id="headline">
        <h1 style="float:left;">Resultats et Cotes</h1>
        <select style="float:right;" name="sltPhase" onchange="changePhase(this.value)"><? foreach ($engine->getPhasesPlayed() as $phase) {
    echo "<option value=\"" . $phase['phaseID'] . "\"" . (($phaseID == $phase['phaseID']) ? "selected=\"selected\"" : "") . ">" . $phase['name'] . "</option>";
} ?></select>
        &nbsp;<br /><br />
    </div>
    <br />
    <? foreach ($phases as $phase) { ?>
        <div class="tag_cloud">
            <span style="font-size: 150%"><? echo $phase['name']; ?></span>
            <table width="100%">
                <?
                $results = $engine->getResultsByPhase($phase['phaseID'], false);
                $lastDate = "";
                foreach ($results as $result) {
                    if ($lastDate != $result['dateStr']) {
                        ?>
                        <tr>
                            <td colspan="2"></td>
                            <td colspan="3" style="text-align:center;"><br /><i><? echo $result['dateStr']; ?></i></td>
                            <td></td>
                        </tr>
                        <?
                    }
                    $lastDate = $result['dateStr'];
                    $odds = $engine->getOddsByMatch($result['matchID']);

                    // Stats paris
                    $exact_bets = null;
                    $nb_exact_bets = 0;
                    $str_exact_bets = "";
                    if($result['scoreMatchA'] && $result['scoreMatchB']) {
                        $exact_bets = $engine->getBestPronosByMatch($result['matchID'], $result['scoreMatchA'], $result['scoreMatchB'], EXACT_SCORE);
                        $nb_exact_bets = count($exact_bets);
                        if ($nb_exact_bets == 0) {
                            $str_exact_bets = "aucun super score";
                        }                      
                        if ($nb_exact_bets == 1) {
                            $str_exact_bets = "1 super score";
                        }
                        if ($nb_exact_bets > 1) {
                            $str_exact_bets = $nb_exact_bets . " supers scores";
                        }
                    }
                    ?>
                    <tr>
                        <td width="5%" align="left" style="white-space: nowrap; font-size: 7pt;" rowspan="4"></td>
                        <td id="m_<? echo $result['matchID']; ?>_team_A" width="32%" rowspan="3" style="text-align: right;background-color: <? echo $result['COLOR_A']; ?>;"><? echo $result['teamAname']; ?> <img src="/include/theme/<? echo $config['template']; ?>/images/fanions/<? echo $result['teamAname']; ?>.gif" alt="<? echo $result['teamAname']; ?>" /></td>
                        <td width="12%" style="text-align:center;font-weight:600;font-size:15px;"><? echo $result['scoreMatchA']; ?></td>
                        <td width="7%" style="text-align:center; font-weight:300; font-size:9px;" rowspan="2"></td>
                        <td width="12%" style="text-align:center;font-weight:600;font-size:15px;"><? echo $result['scoreMatchB']; ?></td>
                        <td id="m_<? echo $result['matchID']; ?>_team_B" width="32%" rowspan="3" style="text-align: left; background-color: <? echo $result['COLOR_B']; ?>;"><img src="/include/theme/<? echo $config['template']; ?>/images/fanions/<? echo $result['teamBname']; ?>.gif" alt="<? echo $result['teamBname']; ?>" /> <? echo $result['teamBname']; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:center;color:blue;font-weight:300;font-size:9px;"><? echo $odds['A_AVG']; ?></td>
                        <td style="text-align:center;color:blue;font-weight:300;font-size:9px;"><? echo $odds['B_AVG']; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:center;color:green;font-weight:300;font-size:9px;"><? echo $odds['A_WINS']; ?>/1</td>
                        <td style="text-align:center;color:green;font-weight:300;font-size:9px;"><? echo $odds['NUL']; ?>/1</td>
                        <td style="text-align:center;color:green;font-weight:300;font-size:9px;"><? echo $odds['B_WINS']; ?>/1</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align:center;color:red;font-weight:300;font-size:9px;">
                            <a onclick="toggle_exact_bets(<? echo $result['matchID']; ?>);"><span style="color:red;"><? echo $str_exact_bets; ?></span></a>
                            <div id="exact_bets_<? echo $result['matchID']; ?>" style="display:none;">
                            <? foreach ($exact_bets as $exact_bet) { ?>
                                <br /><a href="?op=view_pronos&user=<? echo $exact_bet['userID']; ?>"><b><? echo $exact_bet['username']; ?></b></a>
                            <? } ?>
                            </div>
                        </td>
                    </tr>
        <? } ?>
            </table>
            <br />
            <br />
            <br />
        </div>
    <? } ?>
</div>
<br />
<br />
<br />
<div id="rightcolumn">
<?
$pools = $engine->getPoolsByPhase($phase['phaseID']);
foreach ($pools as $pool) {
    ?>
        <div class="tag_cloud">
            <div class="rightcolumn_headline"><h1 style="color:black;">Groupe <? echo $pool['name']; ?></h1></div>
            <div id="pool_<? echo $pool['name']; ?>_ranking">
                <table style="font-size:9px;">
                    <tr>
                        <td width="80%"><b>Nations</b></td><td width="10%"><b>Pts</b></td><td width="10%"><b>Diff</b></td>
                    </tr>
                    <?
                    $matchs = $engine->getMatchsByPool($pool['poolID']);
                    $teams = $engine->getTeamsByPool($pool['poolID']);
                    $ranked_teams = $engine->getRanking($teams, $matchs, 'scoreMatch', $_SESSION['userID']);
                    //$ranked_teams = array();
                    foreach ($ranked_teams as $team) {
                        ?>
                        <tr<? if (isset($team['style']))
                            echo " style=\"" . $team['style'] . "\""; ?>>
                            <td id="team_<? echo $team['teamID']; ?>"><img width="15px" src="/include/theme/<? echo $engine->config['template']; ?>/images/fanions/<? echo $team['name']; ?>.gif" alt="<? echo $team['name']; ?>" /> <? echo $team['name']; ?></td>
                            <td><? echo $team['points']; ?></td>
                            <td><? echo $team['diff']; ?></td>
                        </tr>
        <? } ?>
                </table>
            </div>
        </div>
        <br />
        <br />
<? } ?>
</div>
