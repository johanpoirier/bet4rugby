<?
$teams = $engine->getUserTeams();
$teams[] = array('userTeamID' => 0, 'name' => 'Sans équipe');
?>
<div id="mainarea">

    <div class="maincontent">
        <div id="headline">
            <h1>Parieurs</h1>
        </div>
    </div>

    <div class="maincontent">

        <div class="tag_cloud">
            <form name="add_user" action="/?op=add_user" method="post">
                <input type="hidden" id="idUser" value="" /> 
                <input type="hidden" id="realname" value="" /> 
                <table>
                    <tr><td width="45%">Login :</td><td width="45%">Pass :</td></tr>
                    <tr><td><input type="text" size="25" name="login" id="login" /></td><td><input type="text" size="25" name="pass" id="pass" /></td></tr>
                    <tr><td>Nom :</td><td>Mail :</td></tr>
                    <tr>
                        <td><input type="text" size="25" name="name" id="name" /></td>
                        <td><input type="text" size="25" name="mail" id="mail" /></td>
                    </tr>
                    <tr><td>Equipe :</td><td>&nbsp;</td></tr>
                    <tr>
                        <td>
                            <select name="sltUserTeam" id="sltUserTeam">
                                <? foreach ($teams as $team) { ?>
                                    <option value="<? echo $team['userTeamID']; ?>"><? echo $team['name']; ?></option>
                                <? } ?>
                            </select>
                        </td>
                        <td>Admin <input type="checkbox" name="admin" id="admin" value="1"/></td>
                    </tr>
                    <tr>
                        <td colspan=2 style="text-align:center;">
                            <input type="submit" name="add_user" id="add_user" value="Ajouter / Modifier" />
                            <input type="submit" name="add_user" id="del_user" value="Supprimer" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <? foreach ($teams as $team) { ?>
            <div id="<? echo $team['userTeamID']; ?>">
                <?
                $users = $engine->getUsersByUserTeam($team['userTeamID'], 'all');
                ?>
                <div class="tag_cloud" id="list_users">
                    <h3><? echo $team['name']; ?></h3>
                    <? foreach ($users as $user) { ?>
                        <div id="user_<? echo $user['userID']; ?>" onclick="getUser(<? echo $user['userID']; ?>)">
                            <? echo $user['name']; ?>
                        </div>
                    <? } ?>
                </div>
            </div>
        <? } ?>

        <div class="tag_cloud">
            <form name="add_csv_users" action="/?op=import_csv_file" method="post" enctype="multipart/form-data">
                Importer un fichier csv ('login;pass;nom;teamname;admin') : <br /><br />
                <input type="file" name="csv_file" size="40" />&nbsp;<input type="submit" name="submit" value="Ok" />
            </form>
        </div>

    </div>

</div>
