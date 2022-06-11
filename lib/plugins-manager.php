<?php
require_once dirname(__FILE__) . "/../classes/Settings.php";

$settingsClass = new \ICEcoder\Settings();

include "headers.php";
include "settings.php";
$t = $text['plugins-manager'];

// Set the plugin data source
$pluginsDataSrc = "https://icecoder.net/plugin-data?format=JSON";

// Now get our plugin data and put into a PHP array
$pluginsDataJS = getData($pluginsDataSrc, 'curl');
$pluginsData = json_decode($pluginsDataJS, true);

// If we have an action to perform
if (false === $demoMode && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && isset($_GET['action'])) {

    // ==========
    // INSTALLING
    // ==========

    if ("install" === $_GET['action']) {

        // Store the plugin zip into the tmp dir
        $target = '../plugins/';
        $zipURL = $pluginsData[$_GET['plugin']]['zipURL'];
        $zipFile = "../tmp/" . basename($zipURL);
        $fileData = getData($zipURL, 'curl');
        file_put_contents($zipFile, $fileData);

        // Now unpack the zip
        $zip = new ZipArchive;
        $zip->open($zipFile);

        // Create all files & dirs, in 1kb chunks
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);

            // Determine output filename
            $file = $target . $name;

            // Create the directories if necessary
            $dir = dirname($file);
            if (false === is_dir($dir)) mkdir($dir, 0777, true);

            // Read from zip and write to disk
            $fpr = $zip->getStream($name);
            if (false === is_dir($file)) {
                $fpw = fopen($file, 'w');
                while ($data = fread($fpr, 1024)) {
                    fwrite($fpw, $data);
                }
                fclose($fpw);
            }
            fclose($fpr);
        }
        $zip->close();

        // Remove the tmp zip file
        unlink($zipFile);

        $ICEcoder["plugins"][] = [
            $pluginsData[$_GET['plugin']]['name'],
            str_replace("images/", "plugins/", $pluginsData[$_GET['plugin']]['icon']),
            $pluginsData[$_GET['plugin']]['style'],
            $pluginsData[$_GET['plugin']]['URL'],
            $pluginsData[$_GET['plugin']]['target'],
            $pluginsData[$_GET['plugin']]['timer']
        ];
    }

    // ============
    // UNINSTALLING
    // ============
    if ("uninstall" === $_GET['action']) {
        // Remove the old plugin
        for ($i = 0; $i < count($ICEcoder["plugins"]); $i++) {
            if ($ICEcoder["plugins"][$i][0] === $pluginsData[$_GET['plugin']]['name']) {
                array_splice($ICEcoder["plugins"], $i, 1);
            }
        }

        // Finally, delete the plugin itself
        $target = '../plugins/';
        $dirName = basename($pluginsData[$_GET['plugin']]['zipURL'], ".zip");
        deletePlugin($target . $dirName . "/");
    }

    // ========
    // UPDATING
    // ========

    if ("update" === $_GET['action']) {
        // Redo the arrays using the form data
        $numPlugins = count($ICEcoder["plugins"]);
        for ($i = 0; $i < $numPlugins; $i++) {
            $timer = intval($_POST['timer' . $i]);
            if (0 === $timer) {
                $timer = "";
            }
            $ICEcoder["plugins"][$i] = [
                $_POST['name' . $i],
                $_POST['icon' . $i],
                $_POST['style' . $i],
                $_POST['URL' . $i],
                $_POST['target' . $i],
                $timer
            ];
        }
    }

    // Now update the config file
    if (true === $settingsClass->updateConfigUsersSettings($settingsFile, ["plugins" => $ICEcoder['plugins']])) {
        // Finally, reload ICEcoder itself if plugin requires it or just the iFrame screen for the user if it doesn't
        if ("install" === $_GET['action'] && "true" === $pluginsData[$_GET['plugin']]['reload']) {
            echo "<script>if (confirm('" . $t['ICEcoder needs to...'] . "')) {parent.window.location.reload(true);} else {window.location='plugins-manager.php?updatedPlugins&csrf=' + parent.ICEcoder.csrf;}</script>";
        } else {
            header("Location: plugins-manager.php?updatedPlugins&csrf=" . $_SESSION["csrf"]);
            echo "<script>window.location = 'plugins-manager.php?updatedPlugins&csrf=' + ICEcoder.csrf;</script>";
        }
        exit;
        // die("<span style='color: #fff'>" . $t['saving plugins'] . "</span>");
    } else {
        echo "<script>parent.ICEcoder.message('" . $t['Cannot update config...'] . " data/" . $settingsFile . " " . $t['and try again'] . "');</script>";
    }
}

// Function to delete the plugin dir & files/dirs inside
function deletePlugin($dir) {
    global $t;
    $theDir = opendir($dir);
    while(false !== ($file = readdir($theDir))) {
        if($file !== "." && $file !== "..") {
            chmod($dir . $file, 0777);
            if(is_dir($dir . $file)) {
                chdir('.');
                deletePlugin($dir . $file . '/');
                if(is_dir($dir . $file)) {
                    rmdir($dir . $file) or die("<span style='color: #fff'>" . $t['couldnt delete dir'] . ": $dir$file</span><br />");
                }
            }
            else {
                unlink($dir . $file) or die("<span style='color: #fff''>" . $t['couldnt delete file'] . ": $dir$file</span><br />");
            }
        }
    }
    closedir($theDir);
    rmdir($dir);
}

$assetsPath = "assets" === $settingsClass->assetsRoot
    ? "../" . $settingsClass->assetsRoot
    : $settingsClass->assetsRoot
?>
<!DOCTYPE html>

<html>
<head>
    <title>ICEcoder <?php echo $ICEcoder["versionNo"];?> plugins manager</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" type="text/css" href="<?php echo $assetsPath;?>/css/resets.css?microtime=<?php echo microtime(true);?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $assetsPath;?>/css/plugins-manager.css?microtime=<?php echo microtime(true);?>">
</head>

<body class="pluginsManager" onkeyup="parent.ICEcoder.handleModalKeyUp(event, 'pluginsManager')" onload="this.focus();">

<h1><?php echo $t['plugins'];?></h1>

<a href="javascript:parent.ICEcoder.showManual('<?php echo $ICEcoder["versionNo"];?>','plugins')" style="position: absolute; top: 26px; right: 20px"><div style="position: relative; display: inline-block; padding: 10px; background: #333; color: #fff; font-size: 18px; z-index: 2"><?php echo $t['Guide to writing...'];?></div></a>
<div class="pluginsPane">
    <?php
    $plugins = $ICEcoder['plugins'];
    if (0 < count($plugins)) {
        ?>
        <div style="display: inline-block; width: 740px; margin-bottom: 30px">
            <h2><?php echo $t['Manage Installed'];?></h2><br>

            <form id="pluginUpdateForm" action="plugins-manager.php?action=update" method="POST">
                <table>
                    <tr>
                        <td colspan="2"></td>
                        <td style="padding-left: 5px"><?php echo $t['URL'];?></td>
                        <td style="padding-left: 5px"><?php echo $t['Target'];?></td>
                        <td style="padding-left: 5px"><?php echo $t['Timer'];?></td>
                    </tr>
                    <?php
                    for ($i = 0; $i < count($plugins); $i++) {
                        echo '<tr>';
                        echo '<td style="padding: 0 10px 8px 0; width: 28px; text-align: center"><img src="../' .
                            $plugins[$i][1] .
                            '" alt="' . $plugins[$i][0] .
                            '"><input type="hidden" name="name' . $i .
                            '" value="'.$plugins[$i][0] .
                            '"><input type="hidden" name="icon' . $i .
                            '" value="' . $plugins[$i][1] .
                            '"><input type="hidden" name="style' . $i .
                            '" value="' . $plugins[$i][2] .
                            '"></td>';
                        echo '<td style="padding: 8px 10px 8px 0; width: 250px; white-space: nowrap">' . $plugins[$i][0] . '</td>';
                        echo '<td style="padding: 0 10px 8px 0"><input type="text" name="URL' . $i . '" value="' . $plugins[$i][3] . '" style="width: 280px"></td>';
                        echo '<td style="padding: 0 10px 8px 0"><input type="text" name="target' . $i . '" value="' . $plugins[$i][4] . '" style="width: 70px"></td>';
                        echo '<td style="padding: 0 0 8px 0"><input type="text" name="timer' . $i . '" value="' . $plugins[$i][5] . '" style="width: 50px"></td>';
                        echo '</tr>';
                    }
                    echo '<tr>';
                    echo '<td colspan="4"></td>';
                    echo '<td style="padding: 3px 0 8px 0"><div style="padding: 5px; background: #2187e7; color: #fff; font-size: 12px; text-align: center; cursor: pointer" onclick="document.getElementById(\'pluginUpdateForm\').submit()">' . $t['Update'] . '</div></td>';
                    echo '</tr>';
                    ?>
                </table>
                <input type="hidden" name="csrf" value="<?php echo $_SESSION["csrf"]; ?>">
            </form>
        </div>
        <?php
        ;};
    ?>

    <div style="display: inline-block; width: 740px">
        <h2><?php echo $t['Install'] . ' / ' . $t['Uninstall'];?></h2><br>

        <?php
        // ZipArchive plugin not available
        if (false === class_exists('ZipArchive')) {
            echo "Sorry, you don't have the ZipArchive class in your PHP installation, or it's not enabled in php.ini.";
        // Cannot get data? Show error info
        } elseif (0 === count($pluginsData)) {
            echo "Sorry, unable to get plugin data. Please make sure you have either curl or fopen available on your server.";
        // Show list of plugins
        } else {
            ?>
            <table>
                <?php
                for ($i = 0; $i < count($pluginsData); $i++) {
                    if (0 === $i % 2) {
                        echo '<tr>' . PHP_EOL;
                    }

                    $installUninstallButton = '<div style="display: inline-block; padding: 5px; background: #2187e7; color: #fff; font-size: 12px; cursor: pointer" onclick="window.location=\'plugins-manager.php?action=install&plugin=' . $i . '&csrf='.$_SESSION["csrf"] . '\'">' . $t['Install'] . '</div>';
                    for ($j = 0; $j < count($plugins); $j++) {
                        if ($pluginsData[$i]['name'] == $plugins[$j][0]) {
                            $installUninstallButton = '<div style="display: inline-block; padding: 5px; background: #333; color: #fff; font-size: 12px; cursor: pointer" onclick="window.location=\'plugins-manager.php?action=uninstall&plugin=' . $i . '&csrf=' . $_SESSION["csrf"] . '\'">' . $t['Uninstall'] . '</div>';
                        }
                    }

                    $reloadExtra = "true" === $pluginsData[$i]['reload'] ? '<br><span style="color: #888">' . $t['Reload after install...'] . '</span>' : '';
                    echo '<td style="padding: 0 10px 18px 0; width: 28px; text-align: center"><img src="https://plugins.icecoder.net/' . $pluginsData[$i]['icon'] . '" alt="'.$pluginsData[$i]['name'] . '"></td>';
                    echo '<td style="padding: 8px 10px 8px 0; width: 250px; white-space: nowrap">' . $pluginsData[$i]['name'] . $reloadExtra . '</td>';
                    $styleExtra = (1 === $i % 2 || $i === count($pluginsData) - 1) ? "0" : "30px";
                    echo '<td style="padding: 3px ' . $styleExtra . ' 8px 0">' . $installUninstallButton . '</td>';

                    if (1 === $i % 2 || $i == count($pluginsData) - 1) {
                        echo PHP_EOL . '</tr>' . PHP_EOL;
                    }
                }
                ?>
            </table>

            <?php
        }
        ?>
    </div>
</div>

<?php
echo $systemClass->getDemoModeIndicator(true);
?>

</body>

</html>
