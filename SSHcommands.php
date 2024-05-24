<?php
require __DIR__ . '/vendor/autoload.php';
use phpseclib3\Net\SSH2;
use phpseclib3\Net\SFTP;

$CNoplStack = $_POST['CNoplStack']; 
$CNappParam = $_POST['CNappParam']; 
$CNcsvPhysiques = $_POST['CNcsvPhysiques']; 
$CNcsvCommuns = $_POST['CNcsvCommuns'];
$MNoplStack = $_POST['MNoplStack']; 
$MNappParam = $_POST['MNappParam']; 
$MNcsvCommuns = $_POST['MNcsvCommuns'];
if (PHP_OS_FAMILY === 'Windows') {
    $ipOBC = $_POST['ipOBC'];
}
$mdpOBC = $_POST['mdpOBC'];
$userOBC = $_POST['userOBC'];

$sshConns = [];
$sftpConns = [];

session_start();
$csvData = $_SESSION['csvDataDeploiement'];
$nbCartes = $_SESSION['nbCartes'];

if (!empty($_SESSION['nomConfig'])) {
    $configName = $_SESSION['nomConfig'];
} else {
    header('Location: Pages/pageDeploiements.php?erreurPasDeConfigSel');
    exit;
}
if (PHP_OS_FAMILY === 'Windows') {
    $_SESSION['ipOBC'] = $ipOBC;
}
$_SESSION['userOBC'] = $userOBC;
$_SESSION['mdpOBC'] = $mdpOBC;

$configDir = __DIR__ . "/Configurations/" . $configName . "/";

if(!empty($csvData))
{
    ini_set('max_execution_time', 1000);

    if (PHP_OS_FAMILY === 'Windows') {
        $sshConn = new SSH2($ipOBC); // $data[2] --> ip
        if (!$sshConn->login($userOBC, $mdpOBC)) { // $data[3] --> utilisateur, $data[4] --> mot de passe
            header('Location: Pages/pageDeploiements.php?erreurSSHConnMN');
            exit;
        }
        $sftpConn = new SFTP($ipOBC);
        if (!$sftpConn->login($userOBC, $mdpOBC)) {
            header('Location: Pages/pageDeploiements.php?erreurSFTPConnMN');
            exit;
        }

        $sshConn->setTimeout(1000);

        if($MNoplStack === 'on')
        {
            $dirToCompress = '';
            $localCompressedFilePath = __DIR__ . '/FichiersDeploiement/v1.2-openPOWERLINK_V2_CAC_MN.zip';
            $remoteCompressedFilePath = '/home/'.$userOBC.'/openPOWERLINK_V2_CAC.zip';
            $remoteDecompressedDest = './';
            $remoteDirPath = $remoteDecompressedDest . 'openPOWERLINK_V2_CAC';
            $errGet1 = 'Location: Pages/pageDeploiements.php?erreurOPLStackMNCreerZip';
            $errGet2 = 'Location: Pages/pageDeploiements.php?erreurOPLStackMNZipIntrouvable';
            $errGet3 = 'Location: Pages/pageDeploiements.php?erreurOPLStackMNTeleversement';
            $errGet4 = 'Location: Pages/pageDeploiements.php?erreurOPLStackMNExtraction';
            
            compressSendDecompress($sftpConn, $sshConn, $dirToCompress, $localCompressedFilePath,
                                    $remoteCompressedFilePath,$remoteDecompressedDest,$remoteDirPath,
                                    $errGet1,$errGet2,$errGet3,$errGet4);

            echo 'oplStackMN ' . $CSVcommuns . ' réussi<br>';
        }

        if($MNappParam === 'on')
        {
            $dirToCompress = '';
            $localCompressedFilePath = __DIR__ . '/FichiersDeploiement/output_'.$nbCartes.'CNs/mnobd.cdc';
            $remoteCompressedFilePath = '/home/'.$userOBC.'/mnobd.cdc';
            $remoteDecompressedDest = '/home/'.$userOBC.'/openPOWERLINK_V2_CAC/apps/common/openCONFIGURATOR_projects/Demo_3CN/output/';
            $remoteDirPath = $remoteDecompressedDest . 'mnobd.cdc';                
            $errGet1 = 'Location: Pages/pageDeploiements.php?erreurMnobdCreerZip';
            $errGet2 = 'Location: Pages/pageDeploiements.php?erreurmnobdZipIntrouvable';
            $errGet3 = 'Location: Pages/pageDeploiements.php?erreurmnobdTeleversement';
            $errGet4 = 'Location: Pages/pageDeploiements.php?erreurmnobdCopie';
            
            compressSendDecompress($sftpConn, $sshConn, $dirToCompress, $localCompressedFilePath,
                                    $remoteCompressedFilePath,$remoteDecompressedDest,$remoteDirPath,
                                    $errGet1,$errGet2,$errGet3,$errGet4);
    
            $dirToCompress = '';
            $localCompressedFilePath = $configDir . '/nbNodes.h';
            $remoteCompressedFilePath = '/home/'.$userOBC.'/nbNodes.h';
            $remoteDecompressedDest = '/home/'.$userOBC.'/openPOWERLINK_V2_CAC/apps/OBC_MN/include/';
            $remoteDirPath = $remoteDecompressedDest . 'nodeId.h';                
            $errGet1 = 'Location: Pages/pageDeploiements.php?erreurNbNodesCreerZip';
            $errGet2 = 'Location: Pages/pageDeploiements.php?erreurNbNodesZipIntrouvable';
            $errGet3 = 'Location: Pages/pageDeploiements.php?erreurNbNodesTeleversement';
            $errGet4 = 'Location: Pages/pageDeploiements.php?erreurNbNodesCopie';
            
            compressSendDecompress($sftpConn, $sshConn, $dirToCompress, $localCompressedFilePath,
                                    $remoteCompressedFilePath,$remoteDecompressedDest,$remoteDirPath,
                                    $errGet1,$errGet2,$errGet3,$errGet4);

            $bashScriptPath = '/home/'.$userOBC.'/openPOWERLINK_V2_CAC/compileAppScript.sh';
            $errGet = 'Location: Pages/pageDeploiements.php?erreurCompileMN';
            compileOplApp($sshConn, $bashScriptPath, $errGet);

            echo 'appParamMN ' . $CSVcommuns . ' réussi<br>';
        }

        if($MNcsvCommuns === 'on')
        {
            // Correct path for the local file
            $dirToCompress = $configDir . '/commonCSVFiles';
            $localCompressedFilePath = $dirToCompress . '.zip';
            $remoteCompressedFilePath = '/home/'.$userOBC.'/commonCSVFiles.zip';
            $remoteDecompressedDest = '/home/'.$userOBC.'/openPOWERLINK_V2_CAC/apps/common/';
            $remoteDirPath = $remoteDecompressedDest . 'commonCSVFiles';
            $errGet1 = 'Location: Pages/pageDeploiements.php?erreurCSVcommunsMNCreerZip';
            $errGet2 = 'Location: Pages/pageDeploiements.php?erreurCSVcommunsMNZipIntrouvable';
            $errGet3 = 'Location: Pages/pageDeploiements.php?erreurCSVcommunsMNTeleversement';
            $errGet4 = 'Location: Pages/pageDeploiements.php?erreurCSVcommunsMNExtraction';

            compressSendDecompress($sftpConn, $sshConn, $dirToCompress, $localCompressedFilePath,
                                    $remoteCompressedFilePath,$remoteDecompressedDest,$remoteDirPath,
                                    $errGet1,$errGet2,$errGet3,$errGet4);

            echo 'CSVcommunsMN ' . $CSVcommuns . ' réussi<br>';
        }
    }
    else {
        if($MNoplStack === 'on')
        {
            echo $nbCartes . '<br>';
            $dirToCompress = '';
            $localCompressedFilePath = __DIR__ . '/FichiersDeploiement/v1.2-openPOWERLINK_V2_CAC_MN.zip';
            $remoteDecompressedDest = '/home/'.$userOBC.'/';
            $remoteDirPath = $remoteDecompressedDest . 'openPOWERLINK_V2_CAC';
            $ret = system("echo '" . $mdpOBC . "' | su - " . $userOBC . " -c 'rm -R " . $remoteDirPath . "'");
            $ret = system("echo '" . $mdpOBC . "' | su - " . $userOBC . " -c 'unzip -q -o " . $localCompressedFilePath . " -d " . $remoteDecompressedDest . "'");
            $ret = system('echo $?');
            $ret = trim($ret);
            if($ret !== '0')
            {
                header('Location: Pages/pageDeploiements.php?erreurExtractOplStackMN');
                exit;
            }
        }

        //Copie des fichiers local OBC MN et compilation
        if($MNappParam === 'on')
        {
            //exit;
            $fichierMnobd = __DIR__ . '/FichiersDeploiement/output_'.$nbCartes.'CNs/mnobd.cdc';
            echo $fichierMnobd . '<br>';
            $destMnobd = '/home/'.$userOBC.'/openPOWERLINK_V2_CAC/apps/common/openCONFIGURATOR_projects/Demo_3CN/output/mnobd.cdc';
            echo $destMnobd . '<br>';
            
            $ret = system("echo '" . $mdpOBC . "' | su - " . $userOBC . " -c 'rm -R " . $destMnobd . "'");
            if(!system("echo '" . $mdpOBC . "' | su - " . $userOBC . " -c 'cp " . $fichierMnobd . " " . $destMnobd . "'"))
            {
                header('Location: Pages/pageDeploiements.php?erreurCopieMnobd');
                exit;
            }

            $fichierNbNodes = $configDir."nbNodes.h";
            $destNbNodes = '/home/'.$userOBC.'/openPOWERLINK_V2_CAC/apps/OBC_MN/include/nbNodes.h';
            system("echo '" . $mdpOBC . "' | su - " . $userOBC . " -c 'rm -R " . $destNbNodes . "'");
            if(!system("echo '" . $mdpOBC . "' | su - " . $userOBC . " -c 'cp " . $fichierNbNodes . " " . $destNbNodes . "'"))
            {
                header('Location: Pages/pageDeploiements.php?erreurCopieNbNodes');
                exit;
            }

            $bashScriptPath = '/home/'.$userOBC.'/openPOWERLINK_V2_CAC/compileAppScript.sh';
            if(!system("echo '" . $mdpOBC . "' | su - " . $userOBC . " -c \"screen -S compileAppOpl -dm /bin/bash -c 'bash " . $bashScriptPath . "'\""))
            {
                header('Location: Pages/pageDeploiements.php?erreurCompileMN');
                exit;
            }
        }

        if($MNcsvCommuns === 'on')
        {
            // Correct path for the local file
            $dircsvCommun = $configDir . '/commonCSVFiles';
            $remoteDecompressedDest = '/home/'.$userOBC.'/openPOWERLINK_V2_CAC/apps/common/';
            $remoteDirPath = $remoteDecompressedDest . 'commonCSVFiles';
            system("echo '" . $mdpOBC . "' | su - " . $userOBC . " -c 'rm -R " . $remoteDirPath . "'");
            if(!system("echo '" . $mdpOBC . "' | su - " . $userOBC . " -c 'cp -r " . $remoteDirPath . " " . $remoteDecompressedDest . "'"))
            {
                header('Location: Pages/pageDeploiements.php?erreurCopieCSVCommuns');
                exit;
            }
        }
    }

    foreach($csvData as $index => $data)
    {
        $sshConn = new SSH2($data[2]); // $data[2] --> ip
        if (!$sshConn->login($data[3], $data[4])) { // $data[3] --> utilisateur, $data[4] --> mot de passe
            header('Location: Pages/pageDeploiements.php?erreurSSHConn');
            exit;
        }
        $sftpConn = new SFTP($data[2]);
        if (!$sftpConn->login($data[3], $data[4])) {
            header('Location: Pages/pageDeploiements.php?erreurSFTPConn');
            exit;
        }

        if(!empty($sshConn) && !empty($sftpConn))
        {
            $sftpConn->disableDatePreservation();
            $sshConn->setTimeout(1000);

            if($CNoplStack === 'on')
            {
                $dirToCompress = '';
                $localCompressedFilePath = __DIR__ . '/FichiersDeploiement/v1.2-openPOWERLINK_V2_CAC_CN.zip';
                $remoteCompressedFilePath = '/home/'.$data[3].'/openPOWERLINK_V2_CAC.zip';
                $remoteDecompressedDest = '/home/'.$data[3].'/';
                $remoteDirPath = $remoteDecompressedDest . 'openPOWERLINK_V2_CAC';
                $errGet1 = 'Location: Pages/pageDeploiements.php?erreurOPLStackCreerZip';
                $errGet2 = 'Location: Pages/pageDeploiements.php?erreurOPLStackZipIntrouvable';
                $errGet3 = 'Location: Pages/pageDeploiements.php?erreurOPLStackTeleversement';
                $errGet4 = 'Location: Pages/pageDeploiements.php?erreurOPLStackExtraction';
                
                compressSendDecompress($sftpConn, $sshConn, $dirToCompress, $localCompressedFilePath,
                                        $remoteCompressedFilePath,$remoteDecompressedDest,$remoteDirPath,
                                        $errGet1,$errGet2,$errGet3,$errGet4);

                echo 'oplStack ' . $oplStack . ' réussi<br>';
            }
            
            if($CNappParam === 'on')
            {
                // Correct path for the local file
                $dirToCompress = '';
                $localCompressedFilePath = $configDir . '/physicalCSV_CN'.($index+1).'/nodeId.h';
                $remoteCompressedFilePath = '/home/'.$data[3].'/nodeId.h';
                $remoteDecompressedDest = '/home/'.$data[3].'/openPOWERLINK_V2_CAC/apps/CAC_CN/include/';
                $remoteDirPath = $remoteDecompressedDest . 'nodeId.h';                
                $errGet1 = 'Location: Pages/pageDeploiements.php?erreurNodeIdCreerZip';
                $errGet2 = 'Location: Pages/pageDeploiements.php?erreurNodeIdZipIntrouvable';
                $errGet3 = 'Location: Pages/pageDeploiements.php?erreurNodeIdTeleversement';
                $errGet4 = 'Location: Pages/pageDeploiements.php?erreurNodeIdCopie';
                
                compressSendDecompress($sftpConn, $sshConn, $dirToCompress, $localCompressedFilePath,
                                        $remoteCompressedFilePath,$remoteDecompressedDest,$remoteDirPath,
                                        $errGet1,$errGet2,$errGet3,$errGet4);

                $bashScriptPath = '/home/'.$data[3].'/openPOWERLINK_V2_CAC/compileAppScript.sh';
                $errGet = 'Location: Pages/pageDeploiements.php?erreurCompileCN';
                compileOplApp($sshConn, $bashScriptPath, $errGet);

                echo 'appCAC ' . $appCAC . ' réussi<br>';
            }
            
            if($CNcsvPhysiques === 'on')
            {
                // Correct path for the local file
                $dirToCompress = $configDir . '/physicalCSV_CN'.($index+1).'/physicalCONFIG';
                $localCompressedFilePath = $dirToCompress . '.zip';
                $remoteCompressedFilePath = '/home/'.$data[3].'/physicalCONFIG.zip';
                $remoteDecompressedDest = '/home/'.$data[3].'/openPOWERLINK_V2_CAC/apps/CAC_CN/include/';
                $remoteDirPath = $remoteDecompressedDest . 'physicalCONFIG';                
                $errGet1 = 'Location: Pages/pageDeploiements.php?erreurCSVphysiquesCreerZip';
                $errGet2 = 'Location: Pages/pageDeploiements.php?erreurCSVphysiquesZipIntrouvable';
                $errGet3 = 'Location: Pages/pageDeploiements.php?erreurCSVphysiquesTeleversement';
                $errGet4 = 'Location: Pages/pageDeploiements.php?erreurCSVphysiquesExtraction';
                
                compressSendDecompress($sftpConn, $sshConn, $dirToCompress, $localCompressedFilePath,
                                        $remoteCompressedFilePath,$remoteDecompressedDest,$remoteDirPath,
                                        $errGet1,$errGet2,$errGet3,$errGet4);

                echo 'CSVphysiques ' . $CSVphysiques . ' réussi<br>';
            }
            
            if($CNcsvCommuns === 'on')
            {
                // Correct path for the local file
                $dirToCompress = $configDir . '/commonCSVFiles';
                $localCompressedFilePath = $dirToCompress . '.zip';
                $remoteCompressedFilePath = '/home/'.$data[3].'/commonCSVFiles.zip';
                $remoteDecompressedDest = '/home/'.$data[3].'/openPOWERLINK_V2_CAC/apps/common/';
                $remoteDirPath = $remoteDecompressedDest . 'commonCSVFiles';
                $errGet1 = 'Location: Pages/pageDeploiements.php?erreurCSVcommunsCreerZip';
                $errGet2 = 'Location: Pages/pageDeploiements.php?erreurCSVcommunsZipIntrouvable';
                $errGet3 = 'Location: Pages/pageDeploiements.php?erreurCSVcommunsTeleversement';
                $errGet4 = 'Location: Pages/pageDeploiements.php?erreurCSVcommunsExtraction';

                compressSendDecompress($sftpConn, $sshConn, $dirToCompress, $localCompressedFilePath,
                                        $remoteCompressedFilePath,$remoteDecompressedDest,$remoteDirPath,
                                        $errGet1,$errGet2,$errGet3,$errGet4);

                echo 'CSVcommuns ' . $CSVcommuns . ' réussi<br>';
            }
            echo 'réussi1<br>';
        }
        echo 'réussi2<br>';
    }  
    echo 'réussi3<br>';
}
echo 'réussi4<br>';
header('Location: Pages/pageDeploiements.php?deploiementReussi');
exit;

function compressSendDecompress($sftpConn, $sshConn, $dirToCompress, $localCompressedFilePath,
                                $remoteCompressedFilePath,$remoteDecompressedDest,$remoteDirPath,
                                $errGet1,$errGet2,$errGet3,$errGet4)
{
    if(!empty($dirToCompress)) {
        createZipFromDirectory($dirToCompress, $localCompressedFilePath, $errGet1);
    }
    
    // Ensure the local file exists
    if (!file_exists($localCompressedFilePath)) {
        header($errGet2);
        exit;
    }

    // Attempt to upload the file
    if (!$sftpConn->put($remoteCompressedFilePath, $localCompressedFilePath, SFTP::SOURCE_LOCAL_FILE)) {
        header($errGet3);
        exit;
    }

    $ret = $sshConn->exec('rm -R ' . $remoteDirPath);
    if(hasExtension($localCompressedFilePath, ".h") || hasExtension($localCompressedFilePath, ".cdc")) {
        $sshConn->exec('cp ' . $remoteCompressedFilePath . ' ' . $remoteDecompressedDest);
    }
    else {
        $sshConn->exec('unzip -q -o ' . $remoteCompressedFilePath . ' -d ' . $remoteDecompressedDest);
    }
    $ret = $sshConn->exec('echo $?');
    $ret = trim($ret);
    if($ret !== '0')
    {
        header($errGet4);
        exit;
    }
}

function compileOplApp($sshConn, $bashScriptPath,$errGet)
{
    $ret = $sshConn->exec('screen -S compileAppOpl -dm /bin/bash -c \'bash ' . $bashScriptPath . ' \'');
    //echo 'ret : ' . $ret . '<br>';
    $ret = $sshConn->exec('echo $?');
    $ret = trim($ret);
    //echo 'ret : ' . $ret . '<br>';
    if($ret !== '0')
    {
        header($errGet);
        exit;
    }
}

function createZipFromDirectory($sourceDir, $zipFilePath,$errGet)
{
    // Initialize the ZipArchive object
    $zip = new ZipArchive();

    // Try to open the zip file
    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        header($errGet);
        exit;
    }

    // Ensure the source directory has a trailing slash
    $sourceDir = realpath($sourceDir) . DIRECTORY_SEPARATOR;
    $baseDir = basename($sourceDir);

    // Recursive function to add files to the zip
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourceDir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $file) {
        // Skip directories (they will be added automatically)
        if (!$file->isDir()) {
            // Get the real and relative path for the current file
            $filePath = $file->getRealPath();
            $relativePath = $baseDir . DIRECTORY_SEPARATOR . substr($filePath, strlen($sourceDir));

            // Replace backslashes with forward slashes for ZIP format
            $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);

            // Add the file to the zip archive
            $zip->addFile($filePath, $relativePath);
        }
    }

    // Close the zip file
    $zip->close();
}

function hasExtension($filename, $extension)
{
    // Extract the file extension
    $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);

    // Compare the extracted extension with the desired extension
    return strtolower($fileExtension) === strtolower(ltrim($extension, '.'));
}


?>