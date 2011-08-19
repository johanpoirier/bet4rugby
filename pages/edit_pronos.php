<?
	$userId = $_SESSION['userID'];
	if(isset($_REQUEST['user'])) $userId = $_REQUEST['user'];
	$user = $engine->getUser($userId);
	$mode = 0;
	if($userId != $_SESSION['userID']) $mode = 1;
	if(($_SESSION['status'] == 1) && ($userId != $_SESSION['userID'])) $mode = 2;

	$phase = $engine->getPhase(PHASE_ID_ACTIVE);
?>
<div class="maincontent">  
  <div id="headline"><h1>Pronostics de <? echo $user['name']; ?></h1></div>

  <form action="?op=save_pronos" method="post" name="formPronos">
  <input type="hidden" name="userId" value="<? echo $user['userID']; ?>" />
  <!-- BEGIN pools -->    
  <div class="tag_cloud">
    <table width="100%">
    <!-- BEGIN bets -->
<?
	$pronos = $engine->getPronosByUserAndPool($userId, false, 1, $mode);
	$lastDate = "";
	foreach($pronos as $prono) {
		// Bonus ?
		if($prono['scorePronoA'] >= 20) $bonusDisplayA = 'block';
		else $bonusDisplayA = 'none';
		if($prono['scorePronoB'] >= 20) $bonusDisplayB = 'block';
		else $bonusDisplayB = 'none';
		$bonusA = '';
		$bonusB = '';
		if($prono['pnyPronoA'] != NULL) {
			if($prono['pnyPronoA'] == 1) $bonusA = " checked=\"checked\"";
		}
		if($prono['pnyPronoB'] != NULL) {
		  $bonusDisplayB = 'block';
			if($prono['pnyPronoB'] == 1) $bonusB = " checked=\"checked\"";
		}

		if($lastDate != $prono['dateStr']) {
?>
      <tr>
        <td colspan="6" style="text-align:center;"><br /><i><? echo $prono['dateStr']; ?></i></td>
      </tr>
<?
		}
		$lastDate = $prono['dateStr'];
?>
    <tr>
	  	<td width="5%" align="left" style="white-space: nowrap; font-size: 7pt;" rowspan="2">
        (<? echo $prono['teamPool']; ?>)
      </td>
      <td id="m_<? echo $prono['matchID']; ?>_team_A" width="32%" rowspan="2" style="text-align: right;background-color: <? echo $prono['COLOR_A']; ?>;">
        <? echo $prono['teamAname']; ?>
        <img src="/include/theme/<? echo $config['template']; ?>/images/fanions/<? echo $prono['teamAname']; ?>.gif" alt="<? echo $prono['teamAname']; ?>" />
      </td>
      <td width="12%" style="text-align:right;">
        <input type="text" size="2" name="iptScoreTeam_A_<? echo $prono['matchID']; ?>" id="iptScoreTeam_A_<? echo $prono['matchID']; ?>" value="<? echo $prono['scorePronoA']; ?>" onchange="checkScore(this.id); showBonus(<? echo $prono['matchID']; ?>);"<? echo $prono['DISABLED']; ?> />
      </td>
	    <td width="7%" style="text-align:center; font-weight:300; font-size:9px; color:<? echo $prono['COLOR']; ?>;" rowspan="2">
        <? echo $prono['POINTS']; ?><br />
        <span style="color:grey;">
          <? echo $prono['DIFF']; ?>
        </span>
      </td>
      <td width="12%" style="text-align:left;">
        <input type="text" size="2" name="iptScoreTeam_B_<? echo $prono['matchID']; ?>" id="iptScoreTeam_B_<? echo $prono['matchID']; ?>" value="<? echo $prono['scorePronoB']; ?>" onchange="checkScore(this.id); showBonus(<? echo $prono['matchID']; ?>);"<? echo $prono['DISABLED']; ?> />
      </td>
      <td id="m_<? echo $prono['matchID']; ?>_team_B" width="32%" rowspan="2" style="text-align: left; background-color: <? echo $prono['COLOR_B']; ?>;">
        <img src="/include/theme/<? echo $config['template']; ?>/images/fanions/<? echo $prono['teamBname']; ?>.gif" alt="<? echo $prono['teamBname']; ?>" />
        <? echo $prono['teamBname']; ?>
      </td>
    </tr>

	  <tr>
		  <td style="text-align:center;color:blue;font-weight:300;font-size:9px;"><? echo $prono['scoreMatchA']; ?></td>
		  <td style="text-align:center;color:blue;font-weight:300;font-size:9px;"><? echo $prono['scoreMatchB']; ?></td>
    </tr>

    <!--tr>
      <td></td>
	    <td colspan="2" align="right" class="bonus">
	      <div id="bonus_A_<? echo $prono['matchID']; ?>" style="display:<? echo $bonusDisplayA; ?>;">
			    Bonus Off <input type="checkbox" name="iptBonus_A_<? echo $prono['matchID']; ?>" value="1"<? echo $bonusA; ?><? echo $prono['DISABLED']; ?> />
			  </div>
			</td>
			<td></td>
			<td colspan="2" align="left" class="bonus">
	      <div id="bonus_B_<? echo $prono['matchID']; ?>" style="display:<? echo $bonusDisplayB; ?>;">
          <input type="checkbox" name="iptBonus_B_<? echo $prono['matchID']; ?>" value="1"<? echo $bonusB; ?><? echo $prono['DISABLED']; ?> /> Bonus Off
        </div>
	    </td>
    </tr-->

	  <tr>
	  	<td colspan="6">&nbsp;</td>
	  </tr>
      <!-- END bets -->
<?	} ?>
    </table>
	<br /><br />
	<br />
<?	if($mode != 1) { ?>
	<center>
		<input type="image" src="/include/theme/<? echo $engine->config['template']; ?>/images/submit.gif" name="iptSubmit" />
	</center>
<?	} ?>
  </div>
  </form>
</div>

<br /><br /><br />
<div id="rightcolumn">  
<?
	$pools = $engine->getPoolsByPhase();
	foreach($pools as $pool) {
?>
	<div class="tag_cloud">
      <div class="rightcolumn_headline"><h1 style="color:black;">Groupe <? echo $pool['name']; ?></h1></div>
      <div id="pool_<? echo $pool['name']; ?>_ranking">
        <table style="font-size:9px;">
          <tr>
            <td width="80%"><b>Nations</b></td><td width="10%"><b>Pts</b></td><td width="10%"><b>Diff</b></td>
          </tr>
<?
		$pronos = $engine->getPronosByUserAndPool($_SESSION['userID'], $pool['poolID']);
    $teams = $engine->getTeamsByPool($pool['poolID']);
		$ranked_teams = $engine->getRanking($teams, $pronos, 'scoreProno', $_SESSION['userID']);
		//$ranked_teams = array();
		foreach($ranked_teams as $team) {
?>
          <tr<? if(isset($team['style'])) echo " style=\"".$team['style']."\""; ?>>
            <td id="team_<? echo $team['teamID']; ?>"><img width="15px" src="/include/theme/<? echo $engine->config['template']; ?>/images/fanions/<? echo $team['name']; ?>.gif" alt="<? echo $team['name']; ?>" /> <? echo $team['name']; ?></td>
            <td><? echo $team['points']; ?></td>
            <td><? echo $team['diff']; ?></td>
          </tr>
<?		} ?>
        </table>
      </div>
	</div>
	<br /><br />
<?	} ?>
</div>
  