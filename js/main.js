/**
 *
 */
function over_effect(e,state){
    if(document.all)
        source4=event.srcElement
    else if(document.getElementById)
        source4=e.target
    if(source4.className=="menulines")
        source4.style.borderStyle=state
    else {
        while(source4.tagName!="TABLE"){
            source4=document.getElementById? source4.parentNode : source4.parentElement
            if (source4.className=="menulines")
                source4.style.borderStyle=state
        }
    }
}

/**
 *
 */
function showHide(id) {
    var div = document.getElementById(id);
    div.style.display = (div.style.display=='none')?'block':'none';
}

/**
 *
 */
function showPnyAR(id, scoreA, scoreB) {
    scoreRetourA = $F('iptScoreTeam_A_'+id);
    scoreRetourB = $F('iptScoreTeam_B_'+id);
    if(!scoreA) scoreA = 0;
    if(!scoreB) scoreB = 0;

    if((scoreRetourA.length > 0) && (scoreRetourB.length > 0) && (scoreA == scoreRetourA) && (scoreB == scoreRetourB)) {
        $('pny_'+id).style.display = 'block';
    }
    else {
        $('pny_'+id).style.display = 'none';
    }
}

/**
 *
 */
function showPny(idMatch) {
    scoreA = $F('scoreTeam_A_'+idMatch);
    scoreB = $F('scoreTeam_B_'+idMatch);

    if((scoreA.length > 0) && (scoreB.length > 0) && (scoreA == scoreB)) {
        $('rbPny_A_'+idMatch).value = 'A';
        $('rbPny_B_'+idMatch).value = 'B';
        $('pny_'+idMatch).style.display = 'block';
    }
    else {
        $('pny_'+idMatch).style.display = 'none';
        $('rbPny_A_'+idMatch).value = null;
        $('rbPny_B_'+idMatch).value = null;
    }
}

/**
 *
 */
function showBonus(idMatch) {
/*scoreA = $F('iptScoreTeam_A_'+idMatch);
	scoreB = $F('iptScoreTeam_B_'+idMatch);

	if((scoreA.length > 0) && (scoreB.length > 0)) {
    if(scoreA >= 20) {
		  $('bonus_A_'+idMatch).style.display = 'block';
	  }
	  else {
		  $('bonus_A_'+idMatch).style.display = 'none';
	  }

    if(scoreB >= 20) {
		  $('bonus_B_'+idMatch).style.display = 'block';
	  }
	  else {
		  $('bonus_B_'+idMatch).style.display = 'none';
	  }
	}*/
}

/**
 *
 */
function checkScore(id) {
    ok = 0;
    value = $F(id);
  
    var regExpressionObj = /^[0-9]{1,3}$/;
    regRes = regExpressionObj.test(value);
    ok = (regRes && (value != 1) && (value != 2) && (value != 4)) ? 1 : 0;

    if(ok == 0) {
        alert("Veuillez entrer un score valide !");
        $(id).value = "";
    }
}

function saveTag(userTeamID) {
    if(!userTeamID) userTeamID = -1;
    tag = $("#tag").val();

    $.ajax({
        type: "POST",
        url: "/pages/admin/requests/manager.php",
        data: "op=saveTag&userTeamID=" + userTeamID + "&tag=" + tag,
        success: function(data) {
            $("#tags").html(data);
            $("#tag").val("");
        }
    });

    return false;
}

function delTag(tagID, userTeamID) {
    if(confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
        if(!userTeamID) userTeamID = -1;
        $.ajax({
            type: "POST",
            url: "/pages/admin/requests/manager.php",
            data: "op=delTag&tagID=" + tagID + "&userTeamID=" + userTeamID,
            success: function(data) {
                $("#tags").html(data);
            }
        });
    }
}

function loadTags(userTeamID, startTag) {
    if(!startTag) startTag = 0;
    if(!userTeamID) userTeamID = -1;
    $.ajax({
        type: "POST",
        url: "/pages/admin/requests/manager.php",
        data: "op=getTags&start=" + startTag + "&userTeamID=" + userTeamID,
        success: function(data) {
            $('#tags').html(data);
        }
    });

    return false;
}