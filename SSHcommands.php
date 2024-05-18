<?php
require __DIR__ . '/vendor/autoload.php';
use phpseclib3\Net\SSH2;
use phpseclib3\Net\SFTP;

$oplStack = $_POST['oplStack']; 
$appCAC = $_POST['appCAC']; 
$CSVphysiques = $_POST['CSVphysiques']; 
$CSVcommuns = $_POST['CSVcommuns'];
$sshConns[] = [];
$sftpConns[] = [];

session_start();
$csvData = $_SESSION['csvData'];

foreach($csvData as $data)
{
    $sshConns[] = new SSH2($data[2]); // $data[2] --> ip
    if (!$sshConns->login($data[3], $data[4])) { // $data[3] --> utilisateur, $data[4] --> mot de passe
        throw new \Exception('Login failed');
    }
    $sftpConns[] = new SFTP($data[2]);
    if (!$sftp->login($data[3], $data[4])) {
        throw new \Exception('Login failed');
    }
}

foreach($sftpConns as $sftpConn)
{
    $sftpConn->disableDatePreservation();
    $sftp->put('/home/pi4/testSSH_PHP', __DIR__ . 'ok', SFTP::SOURCE_LOCAL_FILE);
}



if($oplStack == true)
{

}

if($appCAC == true)
{
    
}

if($CSVphysiques == true)
{
    
}

if($CSVcommuns == true)
{
    
}



?>