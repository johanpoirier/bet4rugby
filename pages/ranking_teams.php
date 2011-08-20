<script type="text/javascript">
<!--
	var fillTags = function(obj) {
		//document.getElementById('teamsDivA').innerHTML = obj.responseText;
		window.location.href = "/?op=view_ranking";
	}

	function saveTag() {
		var tag = document.getElementById('tag').value;
		var XHR = new XHRConnection();
		XHR.resetData();
		XHR.appendData("op", "saveTag");
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
<?
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
<?

	foreach($teams as $team) {
?>
	<div class="list_element">
		<table>
			<tr>
				<td width="45" style="font-size:80%;text-align:center;"><strong><? echo $team['rank']; ?></strong> <? echo $team['lastRank']; ?></td>
				<td width="200" style="font-size:70%"><strong><? echo $team['name']; ?></a></strong></td>
				<td width="100" style="font-size:70%;text-align:center;"><? echo $team['nbUsersActifs']."/".$team['nbUsersTotal']; ?></td>
				<td width="50" style="font-size:70%;text-align:center;"><strong><? echo $team['avgPoints']; ?></strong></td>
				<td width="50" style="font-size:70%;text-align:center;"><? echo $team['maxPoints']; ?></td>
				<td width="50" style="font-size:70%;text-align:center;"><? echo $team['totalPoints']; ?></td>
			</tr>
		</table>
	</div>
<?	} ?>
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
			<div id="tags">
<?
	$tags = $engine->getTags(0,25);
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
