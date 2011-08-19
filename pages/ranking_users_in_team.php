<?
	$userId = $_SESSION['userID'];
	if(isset($_REQUEST['user'])) $userId = $_REQUEST['user'];
	$currentUser = $engine->getUser($userId);
	$mode = 0;
	if($_SESSION['status'] == 1) $mode = 2;

	$users = $engine->loadRankingInTeams($currentUser['userTeamID']);

?><script type="text/javascript">
<!--
	var fillTags = function(obj) {
		window.location.href = "/?op=view_ranking_users_in_team";
	}

	function saveTag() {
		var tag = document.getElementById('tag').value;
		var XHR = new XHRConnection();	
		XHR.resetData();
		XHR.appendData("op", "saveTag");
		XHR.appendData("userTeamID", <? echo $currentUser['userTeamID']; ?>);
		XHR.appendData("tag", tag);
		XHR.sendAndLoad("/pages/admin/requests/manager.php", "POST", fillTags);

		return false;
	}

	function delTag(tagID) {
		var XHR = new XHRConnection();	
		XHR.resetData();
		XHR.appendData("op", "delTag");
		XHR.appendData("tagID", tagID);
		XHR.sendAndLoad("/pages/admin/requests/manager.php", "POST", fillTags);
	}
//-->
</script>
<div class="maincontent">
		<div id="headline">
			<table width="100%">
				<tr>
					<td width="55%"><h1>Classement : <? echo $currentUser['team']; ?></h1></td>
					<td align="center" width="15%"><a href="/?op=view_ranking">Général</a></td>
					<td align="center" width="20%"><a href="/?op=view_ranking_teams">Par équipes</a></td>
					<td align="center" width="10%"><a href="/?op=view_ranking_users_in_team"><strong>Interne</strong></a></td>
				</tr>
			</table>
		</div>
	</div>

  <div class="maincontent">		
	<table>
		<tr>
			<td width="45" style="font-size:80%;text-align:center;"><i>Rang</i></td>
			<td width="200" style="font-size:80%"><i>Parieur</i></span>
			<td width="60" style="font-size:80%;text-align:center;"><i>Points</i></td>
			<td width="60" style="font-size:80%;text-align:center;"><i>R&eacute;sultats Exacts</i></td>
			<td width="60" style="font-size:80%;text-align:center;"><i>Scores Exacts</i></td>
			<td width="60" style="font-size:80%;text-align:center;"><i>Différence</i></td>
		</tr>
	</table>

	<!-- BEGIN users -->
<?

	foreach($users as $user) {
?>
	<div class="list_element">
		<table>
			<tr>
				<td width="45" style="font-size:80%;text-align:center;"><strong><? echo $user['RANK']; ?></strong> <? echo $user['LAST_RANK']; ?></td>
				<td width="200" style="font-size:70%"><strong><? if($mode == 2) echo $user['VIEW_BETS']; ?><? echo $user['NAME']; ?><? if($mode == 2) echo "</a>"; ?></strong> <? echo $user['NB_BETS']; ?></td>
				<td width="60" style="font-size:70%;text-align:center;"><strong><? echo $user['POINTS']; ?></strong></td>
				<td width="60" style="font-size:70%;text-align:center;"><? echo $user['NBRESULTS']; ?></td>
				<td width="60" style="font-size:70%;text-align:center;"><? echo $user['NBSCORES']; ?></td>
				<td width="60" style="font-size:70%;text-align:center;"><? echo $user['DIFF']; ?></td>
			</tr>
		</table>
	</div>
<?	} ?>
	<!-- END users -->
  </div>

	<div id="rightcolumn">
		<div class="tag_cloud">
			<div class="rightcolumn_headline"><h1 style="color:black;">TeamBoard</h1></div>
			<div id="tag_0" styAle="text-align:center;"><br />
				<form onsubmit="return saveTag();">
					<input type="text" id="tag" value="" size="20" /><br />
					<span style="font-size:8px;">(Entrée pour envoyer)</span><br /><br />
				</form>
			</div>
			<div id="tags">
<?
	$tags = $engine->getTags(0, 25, $currentUser['userTeamID']);
	foreach($tags as $tag) {
?>
				<div id="tag_<? echo $tag['tagID']; ?>"><img onclick="delTag(<? echo $tag['tagID']; ?>)" src="/include/theme/<? echo $engine->config['template']; ?>/images/del.png" alt="Supprimer" />
					<u><? echo $tag['date']; ?><br /><b><? echo $tag['name']; ?></b></u><br />
					<? echo $tag['tag']; ?><br /><br />
				</div>
<?	} ?>
			</div>
			<div id="navig" style="font-size:10px;text-align:center;">
				<!--{NAVIG}-->
			</div>
		</div>
	</div>
