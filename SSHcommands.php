<?php
require __DIR__ . '/vendor/autoload.php';
use phpseclib3\Net\SSH2;
use phpseclib3\Net\SFTP;

$oplStack = $_POST['oplStack']; 
$appCAC = $_POST['appCAC']; 
$CSVphysiques = $_POST['CSVphysiques']; 
$CSVcommuns = $_POST['CSVcommuns'];
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

$configDir = __DIR__ . "/Configurations/" . $configName . "/";
if(!empty($csvData))
{
    //Copie des fichiers local OBC MN et compilation
    $fichierMnobd = __DIR__ . '/FichiersDeploiement/output_'.$nbCartes.'CNs/mnobd.cdc';
    $OBCuser = system('whoami', $ret);
    if(!$OBCuser)
    {
        header('Location: Pages/pageDeploiements.php?erreurWhoami');
        exit;
    }
    
    $destMnobd = '/home/'.$OBCuser.'/openPOWERLINK_V2_CAC/apps/common/openCONFIGURATOR_projects/Demo_3CN/output/mnobd.cdc';
    if(!system('cp '.$fichierMnobd.' '.$destMnobd, $ret))
    {
        header('Location: Pages/pageDeploiements.php?erreurCopieMnobd');
        exit;
    }

    $fichierNbNodes = $configDir."nbNodes.h";
    $destNbNodes = '/home/'.$OBCuser.'/openPOWERLINK_V2_CAC/apps/OBC_MN/include/nbNodes.h';
    if(!system('cp '.$fichierNbNodes.' '.$destNbNodes, $ret))
    {
        header('Location: Pages/pageDeploiements.php?erreurCopieMnobd');
        exit;
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

            if($oplStack === 'on')
            {
                $dirToCompress = '';
                $localCompressedFilePath = __DIR__ . '/FichiersDeploiement/openPOWERLINK_V2_CAC.zip';
                $remoteCompressedFilePath = '/home/'.$data[3].'/openPOWERLINK_V2_CAC.zip';
                $remoteDecompressedDest = './';
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
            
            if($appCAC === 'on')
            {
                // Correct path for the local file
                $dirToCompress = '';
                $localCompressedFilePath = __DIR__ . '/FichiersDeploiement/CAC_CN.zip';
                $remoteCompressedFilePath = '/home/'.$data[3].'/CAC_CN.zip';
                $remoteDecompressedDest = '/home/'.$data[3].'/openPOWERLINK_V2_CAC/apps/';
                $remoteDirPath = $remoteDecompressedDest . 'CAC_CN';
                $errGet1 = 'Location: Pages/pageDeploiements.php?erreurAppCACCreerZip';
                $errGet2 = 'Location: Pages/pageDeploiements.php?erreurAppCACZipIntrouvable';
                $errGet3 = 'Location: Pages/pageDeploiements.php?erreurAppCACTeleversement';
                $errGet4 = 'Location: Pages/pageDeploiements.php?erreurAppCACExtraction';
                
                compressSendDecompress($sftpConn, $sshConn, $dirToCompress, $localCompressedFilePath,
                                        $remoteCompressedFilePath,$remoteDecompressedDest,$remoteDirPath,
                                        $errGet1,$errGet2,$errGet3,$errGet4);

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

                echo 'appCAC ' . $appCAC . ' réussi<br>';
            }
            
            if($CSVphysiques === 'on')
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
                
                echo 10 . '<br>';
                compressSendDecompress($sftpConn, $sshConn, $dirToCompress, $localCompressedFilePath,
                                        $remoteCompressedFilePath,$remoteDecompressedDest,$remoteDirPath,
                                        $errGet1,$errGet2,$errGet3,$errGet4);

                echo 'CSVphysiques ' . $CSVphysiques . ' réussi<br>';
            }
            
            if($CSVcommuns === 'on')
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
        }
    }  
}
header('Location: Pages/pageDeploiements.php?deploiementReussi');


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
    if(hasExtension($localCompressedFilePath, ".h")) {
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