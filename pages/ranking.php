<?php
$users = $engine->loadRanking();
$nbJoueursTotal = $engine->getNbPlayers();
$nbJoueursActifs = $engine->getNbActivePlayers();

?><div class="maincontent">
    <div id="headline">
        <table width="100%">
            <tr>
                <td width="55%"><h1>Classement</h1>(<?php echo $nbJoueursActifs; ?> parieurs actifs sur <?php echo $nbJoueursTotal; ?>)</td>
                <td align="center" width="15%"><a href="/?op=view_ranking"><strong>Général</strong></a></td>
                <td align="center" width="20%"><a href="/?op=view_ranking_teams">Par équipes</a></td>
                <td align="center" width="10%"><a href="/?op=view_ranking_users_in_team">Interne</a></td>
            </tr>
        </table>
    </div>

    <table>
        <tr>
            <th width="10%" style="font-size:80%;text-align:center;"><i>Rang</i></th>
            <th width="30%" style="font-size:80%"><i>Parieur</i></th>
            <th width="20%" style="font-size:80%;text-align:center;"><i>Equipe</i></th>
            <th width="10%" style="font-size:80%;text-align:center;"><i>Points</i></th>
            <th width="10%" style="font-size:80%;text-align:center;"><i>R&eacute;sultats Exacts</i></th>
            <th width="10%" style="font-size:80%;text-align:center;"><i>Super Scores</i></th>
            <th width="10%" style="font-size:80%;text-align:center;"><i>Différence</i></th>
        </tr>

    <!-- BEGIN users -->
<?php
    foreach ($users as $user) {
?>
        <tr class="list_element">
            <td width="10%" style="font-size:80%;text-align:center;"><strong><?php echo $user['RANK']; ?></strong> <?php echo $user['LAST_RANK']; ?></td>
            <td width="30%" style="font-size:70%"><strong><?php echo $user['VIEW_BETS']; ?><?php echo $user['NAME']; ?><?php echo "</a>"; ?></strong> <?php echo $user['NB_BETS']; ?></td>
            <td width="20%" style="font-size:70%;text-align:center;"><?php echo $user['TEAM']; ?></td>
            <td width="10%" style="font-size:70%;text-align:center;"><strong><?php echo $user['POINTS']; ?></strong></td>
            <td width="10%" style="font-size:70%;text-align:center;"><?php echo $user['NBRESULTS']; ?></td>
            <td width="10%" style="font-size:70%;text-align:center;"><?php echo $user['NBSCORES']; ?></td>
            <td width="10%" style="font-size:70%;text-align:center;"><?php echo $user['DIFF']; ?></td>
        </tr>
<?php } ?>

    </table>
    <!-- END users -->
</div>

<aside>
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
</aside>
<script type="text/javascript">
    loadTags();
</script>
