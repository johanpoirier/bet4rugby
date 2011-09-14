<?
session_start();

header("Content-Type: text/html; charset=utf-8");
define('WEB_PATH', "/");
define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . "/");
define('URL_PATH', "/");

require('include/classes/Engine.php');

$debug = false;
$engine = new Engine(false, $debug);

define('LOGIN', (isset($_GET['op']) && ($_GET['op']) == "login"));
define('REGISTER', (isset($_GET['op']) && ($_GET['op']) == "register"));
define('FORGOT_IDS', (isset($_GET['op']) && ($_GET['op']) == "forgot_ids"));
define('AUTHENTIFICATION_NEEDED', (!isset($_SESSION['userID']) && !LOGIN && !REGISTER && !FORGOT_IDS));
define('PHASE_ID_ACTIVE', $engine->getPhaseIDActive());
define('CODE', ( isset($_GET['c']) && !isset($_GET['op'])));

$op = "";
$pageToInclude = "";
global $message;

if (FORGOT_IDS) {
    if (isset($_POST['email'])) {
        $res = $engine->sendIDs($_POST['email']);
        redirect("/?message=" . $res);
    } else {
        $pageToInclude = "pages/forgot_ids.php";
    }
} elseif (CODE) {
    if (isset($_SESSION['userID'])) {
        redirect("/?op=join_group&c=" . $_GET['c']);
    } else {
        $invitation = $engine->getInvitation($_GET['c']);
        if ($invitation['status'] == 1) {
            redirect("/?op=register&c=" . $_GET['c']);
        } elseif ($invitation['status'] == 2) {
            redirect("/?s=" . $_GET['c']);
        } else {
            redirect("/?op=register");
        }
    }
} elseif (REGISTER) {
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['login'])) {
        if ($_POST['password1'] != $_POST['password2']) {
            redirect("/?op=register&message=" . PASSWORD_MISMATCH);
        }
        $userTeamID = 0;
        $code = $_POST['code'];
        if ($code != "") {
            $teamId = $engine->useInvitation($code);
            if ($teamId) {
                $userTeamID = $teamId;
            }
        }
        $status = $engine->addUser($_POST['login'], $_POST['password1'], $_POST['name'], $_POST['firstname'], $_POST['email'], $userTeamID, 0);

        if ($status < 0) {
            redirect("/?op=register&c=" . $_POST['code'] . "&message=" . $status);
        } else {
            redirect("/?message=" . REGISTER_OK);
        }
    }
    if (isset($_GET['message']) && $_GET['message']) {
        $message = $engine->lang['messages'][$_GET['message']];
    }
    $pageToInclude = "pages/register.php";
} elseif (AUTHENTIFICATION_NEEDED) {
    if (isset($_GET['message']) && $_GET['message']) {
        $message = $engine->lang['messages'][$_GET['message']];
    }
    $pageToInclude = "pages/login.php";
} else {
    if (isset($_REQUEST['op'])) {
        $op = $_REQUEST['op'];
    }
    switch ($op) {
        case "login":
            if ($engine->login($_POST['login'], $_POST['pass'])) {
                if (isset($_POST['code']) && ($_POST['code'] != "")) {
                    redirect("/?c=" . $_POST['code']);
                } else {
                    $pageToInclude = "pages/ranking.php";
                }
            } else {
                $pageToInclude = "pages/login.php";
            }
            break;

        case "logout":
            session_destroy();
            redirect("/");
            break;

        case "account":
            if (isset($_GET['message']) && $_GET['message']) {
                $message = $engine->lang['messages'][$_GET['message']];
            }
            $pageToInclude = "pages/account.php";
            break;

        case "change_account":
            if (isset($_GET['message']) && $_GET['message']) {
                $message = $engine->lang['messages'][$_GET['message']];
            }
            $pageToInclude = "pages/change_account.php";
            break;

        case "update_profile":
            $message = "";
            $pwd = "";
            if ((strlen($_POST['pwd1']) > 0)) {
                if ($_POST['pwd1'] == $_POST['pwd2'])
                    $pwd = $_POST['pwd1'];
                else {
                    redirect("/?op=my_profile&message=" . PASSWORD_MISMATCH);
                }
            }
            if (!$engine->updateProfile($_SESSION['userID'], $_POST['name'], $_POST['email'], $_POST['pwd1'])) {
                redirect("/?op=my_profile&message=" . UNKNOWN_ERROR);
            }
            redirect("/?op=my_profile&message=" . CHANGE_ACCOUNT_OK);
            break;

        case "view_ranking":
            $pageToInclude = "pages/ranking.php";
            break;

        case "view_ranking_teams":
            $pageToInclude = "pages/ranking_teams.php";
            break;

        case "view_ranking_users_in_team":
            $pageToInclude = "pages/ranking_users_in_team.php";
            break;

        case "update_ranking":
            $engine->updateRanking();
            $engine->updateUserTeamRanking();
            $pageToInclude = "pages/ranking.php";
            break;

        case "edit_matchs":
            $pageToInclude = "pages/admin/edit_matchs.php";
            break;

        case "edit_results":
            $pageToInclude = "pages/admin/edit_results.php";
            break;

        case "view_results":
            $pageToInclude = "pages/view_results.php";
            break;

        case "add_match":
            $engine->addMatch($_POST['phase'], $_POST['pool'], $_POST['day'], $_POST['month'], $_POST['hour'], $_POST['minutes'], $_POST['teamA'], $_POST['teamB']);
            $pageToInclude = "pages/admin/edit_matchs.php";
            break;

        case "edit_pronos":
            $pageToInclude = "pages/edit_pronos.php";
            break;

        case "view_pronos":
            $pageToInclude = "pages/view_pronos.php";
            break;

        case "edit_pf":
            $phase = $engine->getPhase(PHASE_ID_ACTIVE);
            if ($phase['phasePrecedente'] == NULL) {
                redirect("/?op=edit_pronos");
            }
            $pageToInclude = "pages/edit_pf.php";
            break;

        case "save_pronos":
            $userId = $_REQUEST['userId'];
            foreach ($_POST as $input => $score) {
                $ipt = strtok($input, "_");
                if ($ipt == "iptScoreTeam") {
                    $team = strtok("_");
                    $matchID = strtok("_");
                    if (!$engine->isDatePassed($matchID)) {
                        $engine->saveProno($userId, $matchID, $team, $score);
                    } else {
                        $user = $engine->getUser($userId);
                        if (($_SESSION['status'] == 1) && ($userId != $_SESSION['userID'])) {
                            $engine->saveProno($userId, $matchID, $team, $score);
                        }
                    }
                }
            }
            $pageToInclude = "pages/edit_pronos.php";
            break;

        case "save_pf":
            $userId = $_REQUEST['userId'];
            foreach ($_POST as $input => $score) {
                $ipt = strtok($input, "_");
                if ($ipt == "iptScoreTeam") {
                    $team = strtok("_");
                    $matchID = strtok("_");
                    if (isset($_POST["iptPny_" . $matchID])) {
                        $pny = $_POST["iptPny_" . $matchID];
                        $engine->saveProno($userId, $matchID, $team, $score, $pny);
                    } else {
                        $engine->saveProno($userId, $matchID, $team, $score);
                    }
                }
            }
            $pageToInclude = "pages/edit_pf.php";
            break;

        case "save_results":
            foreach ($_POST as $input => $score) {
                $ipt = strtok($input, "_");
                if ($ipt == "iptScoreTeam") {
                    $team = strtok("_");
                    $matchID = strtok("_");
                    $bonus = $_POST["sltBonus_" . $team . "_" . $matchID];
                    $pny = '';
                    if (isset($_POST["iptPny_" . $matchID])) {
                        $pny = $_POST["iptPny_" . $matchID];
                    }
                    $engine->saveResult($matchID, $team, $score, $bonus, $pny);
                }
            }
            $pageToInclude = "pages/admin/edit_results.php";
            break;

        case "rules":
            $pageToInclude = "pages/rules.php";
            break;

        case "edit_users":
            $pageToInclude = "pages/admin/users.php";
            break;

        case "add_user":
            $submit = $_POST['add_user'];
            $login = $_POST['login'];
            if ($submit == "Supprimer") {
                $engine->deleteUser($login);
            } else {
                $name = $_POST['name'];
                $pass = $_POST['pass'];
                $mail = $_POST['mail'];
                $userTeamId = $_POST['sltUserTeam'];
                $isAdmin = 0;
                if (isset($_POST['admin'])) {
                    $isAdmin = $_POST['admin'];
                }
                $engine->addOrUpdateUser($login, $pass, $name, $mail, $userTeamId, $isAdmin);
            }

            $pageToInclude = "pages/admin/users.php";
            break;

        case "join_group":
            if (isset($_POST['group'])) {
                $userID = $_SESSION['userID'];
                $password = (isset($_POST['password'])) ? $_POST['password'] : false;
                $code = (isset($_POST['code'])) ? $_POST['code'] : false;
                $status = $engine->joinUserTeam($userID, $_POST['group'], $code, $password);
                redirect("/?op=account&message=" . $status);
            } else {
                if (isset($_GET['message']) && $_GET['message']) {
                    $message = $engine->lang['messages'][$_GET['message']];
                }
                $pageToInclude = "pages/join_group.php";
            }
            break;

        case "leave_group":
            if (!isset($_GET['user_team_id'])) {
                redirect("/?op=account");
            }
            $engine->leaveuserTeam($engine->getCurrentUserId(), $_GET['user_team_id']);
            redirect("/?op=account");
            break;

        case "create_group":
            if (isset($_POST['group_name']) && isset($_POST['password1']) && isset($_POST['password2'])) {
                if ($_POST['password1'] != $_POST['password2']) {
                    redirect("/?op=create_group&message=" . PASSWORD_MISMATCH);
                }
                if ($engine->addGroup('', $_POST['group_name'], $_POST['password1'])) {
                    $group = $engine->getUserTeamByName($_POST['group_name']);
                    $engine->joinUserTeam($engine->getCurrentUserId(), $group['userTeamID'], false);
                    redirect("/?op=invite_friends&message=" . CREATE_GROUP_OK . "&g=" . $group['groupID']);
                } else {
                    redirect("/?op=create_group&message=" . GROUP_ALREADY_EXISTS);
                }
            }
            if (isset($_GET['message']) && $_GET['message']) {
                $message = $engine->lang['messages'][$_GET['message']];
            }
            $pageToInclude = "pages/create_group.php";
            break;

        case "invite_friends":
            if (isset($_POST['type'])) {
                if ($_POST['type'] == 'OUT') {
                    $invitations = array();
                    $emails = array();
                    $nb_invitations = 5;
                    for ($i = 0; $i < $nb_invitations; $i++) {
                        if ((isset($_POST['email_' . $i])) && ($_POST['email_' . $i] != "")) {
                            $invitation = array();
                            $invitation['email'] = $_POST['email_' . $i];
                            $invitation['userTeamID'] = $_POST['userTeamID'];
                            $invitations[] = $invitation;
                            $emails[] = $_POST['email_' . $i];
                        }
                    }
                    $codes = $engine->createUniqInvitations($invitations, $_POST['type']);
                    $status = $engine->sendInvitations($emails, $codes, $_POST['type']);
                    redirect("/?op=invite_friends&message=" . $status . "");
                } elseif ($_POST['type'] == 'IN') {
                    $invitations = array();
                    $emails = array();
                    $nb_invitations = 5;
                    for ($i = 0; $i < $nb_invitations; $i++) {
                        if ((isset($_POST['userID_' . $i])) && ($_POST['userID_' . $i] != "")) {
                            if ($_POST['userID_' . $i] == 0) {
                                continue;
                            }
                            $invitation = array();
                            $user = $engine->getUser($_POST['userID_' . $i]);
                            if (!$user) {
                                continue;
                            }
                            $invitation['userID'] = $_POST['userID_' . $i];
                            $invitation['email'] = $user['email'];
                            $invitation['userTeamID'] = $_POST['userTeamID'];
                            $invitations[] = $invitation;
                            $emails[] = $user['email'];
                        }
                    }
                    $codes = $engine->createUniqInvitations($invitations, $_POST['type']);
                    $status = $engine->sendInvitations($emails, $codes, $_POST['type']);
                    redirect("/?op=invite_friends&message=" . $status . "");
                } else {
                    redirect("/?op=invite_friends");
                }
            }

            $user = $engine->getCurrentUser();
            if (isset($_GET['message']) && $_GET['message']) {
                $message = $engine->lang['messages'][$_GET['message']];
            } elseif (($user['userTeamID'] == "") || ($user['userTeamID'] == 0)) {
                $message = $engine->lang['messages'][INVITE_WITHOUT_GROUP];
            }
            $pageToInclude = "pages/invite_friends.php";
            break;

        default:
            if (AUTHENTIFICATION_NEEDED) {
                $pageToInclude = "pages/login.php";
            } else {
                $pageToInclude = "pages/ranking.php";
            }
            break;
    }
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
    <head>
        <title><? echo $engine->config['title']; ?></title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <link type="text/css" rel="stylesheet" href="include/theme/<? echo $engine->config['template']; ?>/pc.css" />
        <script type="text/javascript" src="/js/jquery.js"> </script>
        <script type="text/javascript" src="/js/main.js"> </script>
    </head>
    <body>
        <div id="main">
<? include_once("include/theme/" . $engine->config['template'] . "/header.php"); ?>
<? if (!$engine->admin) { ?>
                <div id="nav_area">
                    <img src="include/theme/<? echo $engine->config['template']; ?>/images/user_bar.png" usemap="#testbar5" border="0" alt="Menu" />
                    <map name="testbar5" id="testbar5">
                        <area shape="rect" coords="12,4,184,30" href="/?op=view_ranking" alt="Classement" />
                        <area shape="rect" coords="184,4,376,30" href="/?op=edit_pronos" alt="Mes pronostics" />
                        <area shape="rect" coords="376,4,568,30" href="/?op=edit_pf" alt="Ma phase finale" />
                        <area shape="rect" coords="500,4,742,30" href="/?op=view_results" alt="Résultats" />
                    </map>
                </div>
    <?
} else {
    ?>
                <div id="nav_area">
                    <img src="include/theme/<? echo $engine->config['template']; ?>/images/admin_bar.png" usemap="#testbar5" border="0" alt="Menu" />
                    <map name="testbar5" id="testbar5">
                        <area shape="rect" coords="12,4,115,30" href="/?op=view_ranking" alt="Classement" />
                        <area shape="rect" coords="117,4,220,17" href="/?op=edit_pronos" alt="Mes pronostics" />
                        <area shape="rect" coords="117,17,220,30" href="/?op=edit_pf" alt="Ma phase finale" />
                        <area shape="rect" coords="222,4,325,30" href="/?op=view_results" alt="Résultats"  />
                        <area shape="rect" coords="327,4,430,30" href="/?op=edit_users" alt="" />
                        <area shape="rect" coords="432,4,535,30" href="/?op=edit_results" alt="" />
                        <area shape="rect" coords="537,4,640,30" href="/?op=edit_matchs" alt="" />
                        <area shape="rect" coords="642,4,744,30" href="/?op=edit_teams" alt="" />
                    </map>
                </div>
<? } ?>
            <div id="mainarea">
<? if (strlen($pageToInclude) > 0)
    include_once($pageToInclude); ?>
            </div>
            <?
            if (isset($_SESSION["userID"])) {
                include_once("include/theme/" . $engine->config['template'] . "/footer_private.php");
            } else {
                include_once("include/theme/" . $engine->config['template'] . "/footer_public.php");
            }
            ?>
        </div>
    </body>
</html>
