<?
$users = $engine->loadRanking();
$nbJoueursTotal = $engine->getNbPlayers();
$nbJoueursActifs = $engine->getNbActivePlayers();

?><div class="maincontent">
    <div id="headline">
        <table width="100%">
            <tr>
                <td width="55%"><h1>Classement</h1>(<? echo $nbJoueursActifs; ?> parieurs actifs sur <? echo $nbJoueursTotal; ?>)</td>
                <td align="center" width="15%"><a href="/?op=view_ranking"><strong>Général</strong></a></td>
                <td align="center" width="20%"><a href="/?op=view_ranking_teams">Par équipes</a></td>
                <td align="center" width="10%"><a href="/?op=view_ranking_users_in_team">Interne</a></td>
            </tr>
        </table>
    </div>
</div>

<div class="maincontent">		
    <table>
        <tr>
            <td width="45" style="font-size:80%;text-align:center;"><i>Rang</i></td>
            <td width="165" style="font-size:80%"><i>Parieur</i></span>
            <td width="80" style="font-size:80%;text-align:center;"><i>Equipe</i></span>
            <td width="50" style="font-size:80%;text-align:center;"><i>Points</i></td>
            <td width="50" style="font-size:80%;text-align:center;"><i>R&eacute;sultats Exacts</i></td>
            <td width="50" style="font-size:80%;text-align:center;"><i>Super Scores</i></td>
            <td width="50" style="font-size:80%;text-align:center;"><i>Différence</i></td>
        </tr>
    </table>

    <!-- BEGIN users -->
    <?
    foreach ($users as $user) {
        ?>
        <div class="list_element">
            <table>
                <tr>
                    <td width="45" style="font-size:80%;text-align:center;"><strong><? echo $user['RANK']; ?></strong> <? echo $user['LAST_RANK']; ?></td>
                    <td width="165" style="font-size:70%"><strong><? echo $user['VIEW_BETS']; ?><? echo $user['NAME']; ?><? echo "</a>"; ?></strong> <? echo $user['NB_BETS']; ?></td>
                    <td width="80" style="font-size:70%;text-align:center;"><? echo $user['TEAM']; ?></td>
                    <td width="50" style="font-size:70%;text-align:center;"><strong><? echo $user['POINTS']; ?></strong></td>
                    <td width="50" style="font-size:70%;text-align:center;"><? echo $user['NBRESULTS']; ?></td>
                    <td width="50" style="font-size:70%;text-align:center;"><? echo $user['NBSCORES']; ?></td>
                    <td width="50" style="font-size:70%;text-align:center;"><? echo $user['DIFF']; ?></td>
                </tr>
            </table>
        </div>
<? } ?>
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