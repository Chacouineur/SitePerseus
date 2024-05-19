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

if (!empty($_SESSION['nomConfig'])) {
    $configName = $_SESSION['nomConfig'];
} else {
    header('Location: Pages/pageDeploiements.php?erreurPasDeConfigSel');
    exit;
}

$configDir = __DIR__ . "/Configurations/" . $configName . "/";


echo 'oplStack ' . $oplStack . '<br>';
if(!empty($csvData))
{
    foreach($csvData as $data)
    {
        echo '$data[2]' . $data[2] . '<br>';
        echo '$data[2]' . $data[3] . '<br>';
        echo '$data[2]' . $data[4] . '<br>';
        $sshConn = new SSH2($data[2]); // $data[2] --> ip
        if (!$sshConn->login($data[3], $data[4])) { // $data[3] --> utilisateur, $data[4] --> mot de passe
            header('Location: Pages/pageDeploiements.php?erreurSSHConn');
            exit;
        }
        $sshConns[] = $sshConn;
        $sftpConn = new SFTP($data[2]);
        if (!$sftpConn->login($data[3], $data[4])) {
            header('Location: Pages/pageDeploiements.php?erreurSFTPConn');
            exit;
        }
        $sftpConns[] = $sftpConn;
    }
    
    if(!empty($sshConns) && !empty($sftpConns))
    {
        foreach($sftpConns as $sftpConn)
        {
            $sftpConn->disableDatePreservation();        
        }

        if($oplStack === 'on')
        {
            // Correct path for the local file
            $localFilePath = __DIR__ . '/FichiersDeploiement/openPOWERLINK_V2_CAC.zip';
            $remoteFilePath = '/home/pi4/openPOWERLINK_V2_CAC.zip';
            foreach($sftpConns as $sftpConn)
            {
                // Ensure the local file exists
                if (!file_exists($localFilePath)) {
                    throw new \Exception('Local file does not exist: ' . $localFilePath);
                }

                // Attempt to upload the file
                if (!$sftpConn->put($remoteFilePath, $localFilePath, SFTP::SOURCE_LOCAL_FILE)) {
                    throw new \Exception('Failed to upload file to ' . $remoteFilePath);
                }
            }

            foreach($sshConns as $sshConn)
            {
                $sshConn->exec('unzip -q -o ' . $remoteFilePath);
                $ret = $sshConn->exec('echo $?');
                if($ret !== 0)
                {
                    header('Location: Pages/pageDeploiements.php?erreurExtractionOPLStack');
                    exit;
                }
            }
            echo 'oplStack ' . $oplStack . '<br>';
        }
        
        if($appCAC === 'on')
        {
            // Correct path for the local file
            $localFilePath = __DIR__ . '/FichiersDeploiement/CAC_CN.zip';
            $remoteFilePath = '/home/pi4/CAC_CN.zip';
            $remoteFinalDestination = '/home/pi4/openPOWERLINK_V2_CAC/apps/';
            foreach($sftpConns as $sftpConn)
            {
                // Ensure the local file exists
                if (!file_exists($localFilePath)) {
                    throw new \Exception('Local file does not exist: ' . $localFilePath);
                }

                // Attempt to upload the file
                if (!$sftpConn->put($remoteFilePath, $localFilePath, SFTP::SOURCE_LOCAL_FILE)) {
                    throw new \Exception('Failed to upload file to ' . $remoteFilePath);
                }
            }

            foreach($sshConns as $sshConn)
            {
                $sshConn->exec('unzip -q -o ' . $remoteFilePath . ' -d ' . $remoteFinalDestination);
                $ret = $sshConn->exec('echo $?');
                if($ret !== 0)
                {
                    header('Location: Pages/pageDeploiements.php?erreurExtractionAppCAC');
                    exit;
                }
            }
            echo 'appCAC ' . $appCAC . '<br>';
        }
        
        if($CSVphysiques === 'on')
        {
            // Correct path for the local file
            $localFilePath = __DIR__ . '/FichiersDeploiement/physicalCONFIG.zip';
            $remoteFilePath = '/home/pi4/physicalCONFIG.zip';
            $remoteFinalDestination = '/home/pi4/openPOWERLINK_V2_CAC/apps/CAC_CN/include/';
            foreach($sftpConns as $sftpConn)
            {
                // Ensure the local file exists
                if (!file_exists($localFilePath)) {
                    throw new \Exception('Local file does not exist: ' . $localFilePath);
                }

                // Attempt to upload the file
                if (!$sftpConn->put($remoteFilePath, $localFilePath, SFTP::SOURCE_LOCAL_FILE)) {
                    throw new \Exception('Failed to upload file to ' . $remoteFilePath);
                }
            }

            foreach($sshConns as $sshConn)
            {
                $sshConn->exec('unzip -q -o ' . $remoteFilePath . ' -d ' . $remoteFinalDestination);
                $ret = $sshConn->exec('echo $?');
                if($ret !== 0)
                {
                    header('Location: Pages/pageDeploiements.php?erreurExtractionCSVPhysiques');
                    exit;
                }
            }
            echo 'CSVphysiques ' . $CSVphysiques . '<br>';
        }
        
        if($CSVcommuns === 'on')
        {
            // Correct path for the local file
            $localFilePath = __DIR__ . '/FichiersDeploiement/commonCSVFiles.zip';
            $remoteFilePath = '/home/pi4/commonCSVFiles.zip';
            $remoteFinalDestination = '/home/pi4/openPOWERLINK_V2_CAC/apps/common/';
            foreach($sftpConns as $sftpConn)
            {
                // Ensure the local file exists
                if (!file_exists($localFilePath)) {
                    throw new \Exception('Local file does not exist: ' . $localFilePath);
                }

                // Attempt to upload the file
                if (!$sftpConn->put($remoteFilePath, $localFilePath, SFTP::SOURCE_LOCAL_FILE)) {
                    throw new \Exception('Failed to upload file to ' . $remoteFilePath);
                }
            }

            foreach($sshConns as $sshConn)
            {
                $sshConn->exec('unzip -q -o ' . $remoteFilePath . ' -d ' . $remoteFinalDestination);
                $ret = $sshConn->exec('echo $?');
                if($ret !== 0)
                {
                    header('Location: Pages/pageDeploiements.php?erreurExtractionCSVCommuns');
                    exit;
                }
            }
            echo 'CSVcommuns ' . $CSVcommuns . '<br>';
        }
    }
    
}

function createZipFromDirectory($sourceDir, $zipFilePath)
{
    // Initialize the ZipArchive object
    $zip = new ZipArchive();

    // Try to open the zip file
    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        header('Location: Pages/pageDeploiements.php?erreurZipCreation');
        exit;
    }

    // Ensure the source directory has a trailing slash
    $sourceDir = rtrim($sourceDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

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
            $relativePath = substr($filePath, strlen($sourceDir));

            // Replace backslashes with forward slashes for ZIP format
            $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);

            // Add the file to the zip archive
            $zip->addFile($filePath, $relativePath);
        }
    }

    // Close the zip file
    $zip->close();
}


?>