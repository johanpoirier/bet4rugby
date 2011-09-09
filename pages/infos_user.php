<?
if (isset($_SESSION["userID"])) {
    // Next matchs
    $nMatchs = $engine->getNextMatchs();
    ?>
    <div class="info">
        <h5>Prochain match :</h5>
        <?
        foreach ($nMatchs as $match) {
            $delay_str = format_delay($match['delay_sec']);
            echo $match['teamAname'] . " - " . $match['teamBname'] . " (dans " . $delay_str . ")<br />";
        }
        ?>
		<div style="margin: 7px 0 0 30px;"><? include_once("pages/paypal.php"); ?></div>
    </div>
    <?
    $pronos = $engine->getNextPronosByUser($_SESSION['userID']);
    if (sizeof($pronos) > 0) {
        ?>
        <div class="info">
            <h5><? echo sizeof($pronos); ?> pronostic(s) à effectuer :</h5>
            <?
            $nb_pronos = (sizeof($pronos) < 2) ? sizeof($pronos) : 2;
            for ($i = 0; $i < $nb_pronos; $i++) {
                $delay_str = format_delay($pronos[$i]['delay_sec']);
                echo $pronos[$i]['teamAname'] . " - " . $pronos[$i]['teamBname'] . " (dans " . $delay_str . ")<br />";
            }
            if (sizeof($pronos) > 2)
                echo "...";
            ?>
        </div>
        <?
    }
}
?>
