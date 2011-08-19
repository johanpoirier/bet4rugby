<?
	$userId = $_SESSION['userID'];
	if(isset($_REQUEST['user'])) $userId = $_REQUEST['user'];
	$user = $engine->getUser($userId);
	$mode = 0;
	if($userId != $_SESSION['userID']) $mode = 1;
	if(($_SESSION['status'] == 1) && ($userId != $_SESSION['userID'])) $mode = 2;

	$phase = $engine->getPhase(PHASE_ID_ACTIVE);
	//$phases = $engine->getPhaseByPhaseRoot($phase['phasePrecedente']);
	$phases = $engine->getFinalPhasesPlayed();
?>
<div class="maincontent">  
  <div id="headline"><h1>Phase finale de <? echo $user['name']; ?></h1></div>

  <form action="?op=save_pf" method="post" name="formPronos">
  <input type="hidden" name="userId" value="<? echo $user['userID']; ?>" />
  <!-- BEGIN pools -->
<? foreach($phases as $phase) { ?>
  <div class="tag_cloud">
  	<span style="font-size: 150%"><? echo $phase['name']; ?></span>
    <table width="100%">
    <!-- BEGIN bets -->
<?
	$pronos = $engine->getPronosByUserAndPool($userId, false, $phase['phaseID'], $mode);
	$lastDate = "";
	foreach($pronos as $prono) {
		// Pny ?
		$pnyDisplay = 'none';
		$pnyA = '';
		$pnyB = '';
		if($prono['pnyPronoA'] != NULL) {
			$pnyDisplay = 'table-row';
			if($prono['pnyPronoA'] == 1) $pnyA = " checked = 'checked'";
		}
		if($prono['pnyPronoB'] != NULL) {
			if($prono['pnyPronoB'] == 1) $pnyB = " checked = 'checked'";
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
	  	  <td width="5%" align="left" style="white-space: nowrap; font-size: 7pt;" rowspan="2"></td>
        <td id="m_<? echo $prono['matchID']; ?>_team_A" width="32%" style="text-align: right;background-color: <? echo $prono['COLOR_A']; ?>;"><? echo $prono['teamAname']; ?> <img src="/include/theme/<? echo $config['template']; ?>/images/fanions/<? echo $prono['teamAname']; ?>.gif" alt="<? echo $prono['teamAname']; ?>" /></td>
        <td width="12%" style="text-align:right;"><input type="text" size="2" id="scoreTeam_A_<? echo $prono['matchID']; ?>" name="iptScoreTeam_A_<? echo $prono['matchID']; ?>" value="<? echo $prono['scorePronoA']; ?>"<? echo $prono['DISABLED']; ?> onkeyup="showPny(<? echo $prono['matchID']; ?>)" onchange="checkScore(this.id)" /></td>
		    <td width="7%" style="text-align:center; font-weight:300; font-size:9px; color:<? echo $prono['COLOR']; ?>;"><? echo $prono['POINTS']; ?><br /><span style="color:grey;"><? echo $prono['DIFF']; ?></span></td>
        <td width="12%" style="text-align:left;"><input type="text" size="2" id="scoreTeam_B_<? echo $prono['matchID']; ?>" name="iptScoreTeam_B_<? echo $prono['matchID']; ?>" value="<? echo $prono['scorePronoB']; ?>"<? echo $prono['DISABLED']; ?> onkeyup="showPny(<? echo $prono['matchID']; ?>)" onchange="checkScore(this.id)" /></td>
        <td id="m_<? echo $prono['matchID']; ?>_team_B" width="32%" style="text-align: left; background-color: <? echo $prono['COLOR_B']; ?>;"><img src="/include/theme/<? echo $config['template']; ?>/images/fanions/<? echo $prono['teamBname']; ?>.gif" alt="<? echo $prono['teamBname']; ?>" /> <? echo $prono['teamBname']; ?></td>
      </tr>

	    <tr>
		    <td colspan="5" align="center">
			    <div id="pny_<? echo $prono['matchID']; ?>" class="pny" style="display:<? echo $pnyDisplay; ?>">
				    <input type="radio" name="iptPny_<? echo $prono['matchID']; ?>" id="rbPny_A_<? echo $prono['matchID']; ?>" value="A"<? echo $pnyA; ?><? echo $prono['DISABLED']; ?> />
				    drop-goals
				    <input type="radio" name="iptPny_<? echo $prono['matchID']; ?>" id="rbPny_B_<? echo $prono['matchID']; ?>" value="B"<? echo $pnyB; ?><? echo $prono['DISABLED']; ?> />
			    </div>
		    </td>
      </tr>
	    <tr>
	  	  <td></td>
	  	  <td></td>
		    <td style="text-align:center;color:blue;font-weight:300;font-size:9px;"><? echo $prono['scoreMatchA']; ?></td>
		    <td></td>
		    <td style="text-align:center;color:blue;font-weight:300;font-size:9px;"><? echo $prono['scoreMatchB']; ?></td>
  	    <td></td>
      </tr>
	    <tr height="10">
	  	  <td colspan="6"></td>
	    </tr>
      <!-- END bets -->
<?	} ?>
    </table>
	<br />
	<br />
<?	if($mode != 1) { ?>
	<center>
		<input type="image" src="/include/theme/<? echo $engine->config['template']; ?>/images/submit.gif" name="iptSubmit" />
	</center>
<?  } ?>
  </div>
<? } ?>
  </form>
</div>
