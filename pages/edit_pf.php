<?php
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
  <div class="headline"><h1>Phase finale de <?php echo $user['name']; ?></h1></div>

  <form action="?op=save_pf" method="post" name="formPronos">
  <input type="hidden" name="userId" value="<?php echo $user['userID']; ?>" />
  <!-- BEGIN pools -->
<?php foreach($phases as $phase) { ?>
  <div class="tag_cloud">
  	<span style="font-size: 150%"><?php echo $phase['name']; ?></span>
    <table width="100%">
    <!-- BEGIN bets -->
<?php
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
        <td colspan="6" style="text-align:center;"><br /><i><?php echo $prono['dateStr']; ?></i></td>
      </tr>
<?php
		}
		$lastDate = $prono['dateStr'];
?>
      <tr>
	  	  <td width="5%" align="left" style="white-space: nowrap; font-size: 7pt;" rowspan="2"></td>
        <td id="m_<?php echo $prono['matchID']; ?>_team_A" width="32%" style="text-align: right;background-color: <?php echo $prono['COLOR_A']; ?>;"><?php echo $prono['teamAname']; ?> <img src="/include/theme/<?php echo $config['template']; ?>/images/fanions/<?php echo $prono['teamAname']; ?>.gif" alt="<?php echo $prono['teamAname']; ?>" /></td>
        <td width="12%" style="text-align:right;"><input type="number" min="0" max="500" size="2" id="scoreTeam_A_<?php echo $prono['matchID']; ?>" name="iptScoreTeam_A_<?php echo $prono['matchID']; ?>" value="<?php echo $prono['scorePronoA']; ?>"<?php echo $prono['DISABLED']; ?> onkeyup="showPny(<?php echo $prono['matchID']; ?>)" onchange="checkScore(this.id)" /></td>
		    <td width="7%" style="text-align:center; font-weight:300; font-size:9px; color:<?php echo $prono['COLOR']; ?>;"><?php echo $prono['POINTS']; ?><br /><span style="color:grey;"><?php echo $prono['DIFF']; ?></span></td>
        <td width="12%" style="text-align:left;"><input type="number" min="0" max="500" size="2" id="scoreTeam_B_<?php echo $prono['matchID']; ?>" name="iptScoreTeam_B_<?php echo $prono['matchID']; ?>" value="<?php echo $prono['scorePronoB']; ?>"<?php echo $prono['DISABLED']; ?> onkeyup="showPny(<?php echo $prono['matchID']; ?>)" onchange="checkScore(this.id)" /></td>
        <td id="m_<?php echo $prono['matchID']; ?>_team_B" width="32%" style="text-align: left; background-color: <?php echo $prono['COLOR_B']; ?>;"><img src="/include/theme/<?php echo $config['template']; ?>/images/fanions/<?php echo $prono['teamBname']; ?>.gif" alt="<?php echo $prono['teamBname']; ?>" /> <?php echo $prono['teamBname']; ?></td>
      </tr>

	    <tr>
		    <td colspan="5" align="center">
			    <div id="pny_<?php echo $prono['matchID']; ?>" class="pny" style="display:<?php echo $pnyDisplay; ?>">
				    <input type="radio" name="iptPny_<?php echo $prono['matchID']; ?>" id="rbPny_A_<?php echo $prono['matchID']; ?>" value="A"<?php echo $pnyA; ?><?php echo $prono['DISABLED']; ?> />
				    drop-goals
				    <input type="radio" name="iptPny_<?php echo $prono['matchID']; ?>" id="rbPny_B_<?php echo $prono['matchID']; ?>" value="B"<?php echo $pnyB; ?><?php echo $prono['DISABLED']; ?> />
			    </div>
		    </td>
      </tr>
	    <tr>
	  	  <td></td>
	  	  <td></td>
		    <td style="text-align:center;color:blue;font-weight:300;font-size:9px;"><?php echo $prono['scoreMatchA']; ?></td>
		    <td></td>
		    <td style="text-align:center;color:blue;font-weight:300;font-size:9px;"><?php echo $prono['scoreMatchB']; ?></td>
  	    <td></td>
      </tr>
	    <tr height="10">
	  	  <td colspan="6"></td>
	    </tr>
      <!-- END bets -->
<?php	} ?>
    </table>
	<br />
	<br />
<?php	if($mode != 1) { ?>
	<center>
		<input type="image" src="/include/theme/<?php echo $engine->config['template']; ?>/images/submit.gif" name="iptSubmit" />
	</center>
<?php  } ?>
  </div>
<?php } ?>
  </form>
</div>
