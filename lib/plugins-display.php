<?php
include "headers.php";

$onLoadExtras = "";
$pluginsDisplay = "";

// Show plugins
if ($_SESSION['loggedIn']) {
    // Work out the plugins to display to the user
    $pluginsDisplay = "";
    for ($i = 0; $i < count($ICEcoder["plugins"]); $i++) {
        $target = explode(":", $ICEcoder["plugins"][$i][4]);
        $pluginsDisplay .=
            '<a href="' . $ICEcoder["plugins"][$i][3] .
            '" title="' . $ICEcoder["plugins"][$i][0] .
            '" target="' . $target[0] .
            '"><img src="' . $ICEcoder["plugins"][$i][1] .
            '" style="' . $ICEcoder["plugins"][$i][2] .
            '" alt="' . $ICEcoder["plugins"][$i][0] .
            '"></a><br><br>';
    };

    // If we're updating plugins, update those shown
    if (isset($_GET['updatedPlugins'])) {
        echo "<script>parent.document.getElementById('pluginsOptional').innerHTML = '" . str_replace("'" ,"\\'", $pluginsDisplay) . "';</script>";
    }

    // Work out what plugins we'll need to set on a setInterval
    $onLoadExtras = "";
    for ($i = 0; $i < count($ICEcoder["plugins"]); $i++) {
        if ($ICEcoder["plugins"][$i][5] != "") {
            $onLoadExtras .=
                ";ICEcoder.startPluginIntervals(" . $i . ",'" .
                $ICEcoder["plugins"][$i][3] . "','" .
                $ICEcoder["plugins"][$i][4] . "','" .
                $ICEcoder["plugins"][$i][5] .
                "')";
        };
    };

    // If we're updating our plugins, clear existing setIntervals & the array refs, then start new ones
    if (isset($_GET['updatedPlugins'])) {
        ?>
        <script>
            for (let i = 0; i <= parent.ICEcoder.pluginIntervalRefs.length - 1; i++) {
                clearInterval(parent.ICEcoder['plugTimer' + parent.ICEcoder.pluginIntervalRefs[i]]);
            }
            parent.ICEcoder.pluginIntervalRefs = [];
            <?php echo $onLoadExtras . PHP_EOL; ?>
        </script>
        <?php
    }
}
?>
