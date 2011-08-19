<div class="maincontent">
  <div id="headline"><h1>Résultats</h1></div>
  <div id="update_ranking" class="headline"style="color:red;text-align:right;"><a href="/?op=update_ranking">Mettre à jour le classement</a></div>

  <form action="../?op=save_results" method="post" name="formPronos">
  <!-- BEGIN pools -->    
  <div class="tag_cloud">
    <table width="100%">
    <!-- BEGIN matchs -->
<?
	$matchs = $engine->getMatchs();
	$lastDate = "";
	foreach($matchs as $match) {
		// Pny ?
		$pnyDisplay = 'none';
		$pnyA = '';
		$pnyB = '';
		if($match['pnyMatchA'] != NULL) {
			$pnyDisplay = 'table-row';
			if($match['pnyMatchA'] == 1) $pnyA = " checked = 'checked'";
		}
		if($match['pnyMatchB'] != NULL) {
			if($match['pnyMatchB'] == 1) $pnyB = " checked = 'checked'";
		}

    // Match passé ?
		if($lastDate != $match['dateStr']) {
?>
      <tr>
        <td colspan="5" style="text-align: center;"><br /><i><? echo $match['dateStr']; ?></i></td>
      </tr>
<?
		}
		$lastDate = $match['dateStr'];
?>
      <tr>
        <td align="left" style="white-space: nowrap; font-size: 7pt;">(<? echo $match['teamPool']; ?>)</td>
        <td id="m_<? echo $match['matchID']; ?>_team_A" width="40%" style="text-align: right; background-color: <? echo $match['COLOR_A']; ?>;"><? echo $match['teamAname']; ?> <img src="/include/theme/<? echo $config['template']; ?>/images/fanions/<? echo $match['teamAname']; ?>.gif" alt="<? echo $match['teamAname']; ?>" /></td>
        <td width="10%" style="text-align:right;"><input type="text" size="2" name="iptScoreTeam_A_<? echo $match['matchID']; ?>" id="scoreTeam_A_<? echo $match['matchID']; ?>" value="<? echo $match['scoreMatchA']; ?>" onkeyup="showPny(<? echo $match['matchID']; ?>)" onchange="checkScore(this.id)" /></td>
        <td width="10%" style="text-align: left;"><input type="text" size="2" name="iptScoreTeam_B_<? echo $match['matchID']; ?>" id="scoreTeam_B_<? echo $match['matchID']; ?>" value="<? echo $match['scoreMatchB']; ?>" onkeyup="showPny(<? echo $match['matchID']; ?>)" onchange="checkScore(this.id)" /></td>
        <td id="m_<? echo $match['matchID']; ?>_team_B" width="40%" style="text-align: left; background-color: <? echo $match['COLOR_B']; ?>;"><img src="/include/theme/<? echo $config['template']; ?>/images/fanions/<? echo $match['teamBname']; ?>.gif" alt="<? echo $match['teamBname']; ?>" /> <? echo $match['teamBname']; ?></td>
      </tr>
      <tr>
        <td></td>
        <td colspan="2" style="text-align:right;"><select size="1" name="sltBonus_A_<? echo $match['matchID']; ?>"><option value="0"<? if($match['bonusA'] == 0) echo "selected=\"selected\""; ?>>0 pt</option><option value="1"<? if($match['bonusA'] == 1) echo "selected=\"selected\""; ?>>1 pt</option><option value="2"<? if($match['bonusA'] == 2) echo "selected=\"selected\""; ?>>2 pts</option></select></td>
        <td colspan="2" style="text-align:left;"><select size="1" name="sltBonus_B_<? echo $match['matchID']; ?>"><option value="0"<? if($match['bonusB'] == 0) echo "selected=\"selected\""; ?>>0 pt</option><option value="1"<? if($match['bonusB'] == 1) echo "selected=\"selected\""; ?>>1 pt</option><option value="2"<? if($match['bonusB'] == 2) echo "selected=\"selected\""; ?>>2 pts</option></select></td>
      </tr>
	    <tr>
	  	  <td></td>
		    <td colspan="4" align="center">
			    <div id="pny_<? echo $match['matchID']; ?>" class="pny" style="display:<? echo $pnyDisplay; ?>">
				    <input type="radio" id="rbPny_A_<? echo $match['matchID']; ?>" name="iptPny_<? echo $match['matchID']; ?>" value="A"<? echo $pnyA; ?> />
				    drop-goals
				    <input type="radio" id="rbPny_B_<? echo $match['matchID']; ?>" name="iptPny_<? echo $match['matchID']; ?>" value="B"<? echo $pnyB; ?> />
			    </div>
		    </td>
      </tr>
<?	} ?>
    <!-- END matchs -->
    </table>
	<br /><br />
	<br />
	<center>
		<input type="image" src="/include/theme/<? echo $engine->config['template']; ?>/images/submit.gif" name="iptSubmit" />
	</center>
  </div>

  <!--div id="rightcolumn">  
    <div class="tag_cloud">    
      <div class="rightcolumn_headline"><h1>Groupe {pools.POOL}</h1></div>      
      <div id="pool_{pools.POOL}_ranking">
        <table style="font-size:9px;">
          <tr>
            <td width="80%"><b>Nations</b></td><td width="10%"><b>Pts</b></td><td width="10%"><b>Diff</b></td>
          </tr>
          <tr>
            <td id="{pools.teams.ID}_team"><img width="15px" src="{TPL_WEB_PATH}/images/flag/{pools.teams.NAME_URL}.png" /> {pools.teams.NAME}</td>
            <td>{pools.teams.POINTS}</td>
            <td>{pools.teams.DIFF}</td>
          </tr>
        </table>
      </div>      
    </div>    
  </div-->
  </form>
</div>
