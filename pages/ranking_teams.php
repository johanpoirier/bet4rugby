<?php
$teams = $engine->loadUserTeamRanking();
?><div class="maincontent">
    <div id="headline">
        <table width="100%">
            <tr>
                <td width="55%"><h1>Classement par équipe</h1></td>
                <td align="center" width="15%"><a href="/?op=view_ranking">Général</a></td>
                <td align="center" width="20%"><a href="/?op=view_ranking_teams"><strong>Par équipes</strong></a></td>
                <td align="center" width="10%"><a href="/?op=view_ranking_users_in_team">Interne</a></td>
            </tr>
        </table>
    </div>
</div>

<div class="maincontent">
    <table>
        <tr>
            <td width="45" style="font-size:80%;text-align:center;"><i>Rang</i></td>
            <td width="200" style="font-size:80%"><i>Equipe</i></span>
            <td width="100" style="font-size:80%;text-align:center;"><i>Joueurs</i></td>
            <td width="50" style="font-size:80%;text-align:center;"><i>Moyenne</i></td>
            <td width="50" style="font-size:80%;text-align:center;"><i>Max</i></td>
            <td width="50" style="font-size:80%;text-align:center;"><i>Total</i></td>
        </tr>
    </table>

    <!-- BEGIN users -->
    <?php
    foreach ($teams as $team) {
        ?>
        <div class="list_element">
            <table>
                <tr>
                    <td width="45" style="font-size:80%;text-align:center;"><strong><?php echo $team['rank']; ?></strong> <?php echo $team['lastRank']; ?></td>
                    <td width="200" style="font-size:70%"><strong><?php echo $team['name']; ?></a></strong></td>
                    <td width="100" style="font-size:70%;text-align:center;"><?php echo $team['nbUsersActifs'] . "/" . $team['nbUsersTotal']; ?></td>
                    <td width="50" style="font-size:70%;text-align:center;"><strong><?php echo $team['avgPoints']; ?></strong></td>
                    <td width="50" style="font-size:70%;text-align:center;"><?php echo $team['maxPoints']; ?></td>
                    <td width="50" style="font-size:70%;text-align:center;"><?php echo $team['totalPoints']; ?></td>
                </tr>
            </table>
        </div>
    <?php } ?>
    <!-- END users -->
</div>

<div id="rightcolumn">
    <div class="tag_cloud">
        <div class="rightcolumn_headline"><h1 style="color:black;">ChatBoard</h1></div>
        <div id="tag_0" styAle="text-align:center;"><br />
            <form onsubmit="return saveTag();">
                <input type="text" id="tag" value="" size="20" /><br />
                <span style="font-size:8px;">(Entrée pour envoyer)</span><br /><br />
            </form>
        </div>
        <div id="tags"></div>
        <div id="navig" style="font-size:10px;text-align:center;">
            <!--{NAVIG}-->
        </div>
    </div>
</div>
<script type="text/javascript">
    <!--
    loadTags();
    //-->
</script>