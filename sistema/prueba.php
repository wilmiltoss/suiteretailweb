<?php
/**
 * Pruebe la conexión sftp y descargue archivos remotos
 */
 
$config = [
    'host' => '172.31.11.11',
    'username'=>'webdesa',
    'password'=>'WWWd3v3l',
    'port'=>'22',
];
 $ localPath = 'C:\\BALANZAS\\'; // Ruta de almacenamiento local
$remotePath = '/img/atras.png';
 
$sftp = new Sftp($config);
 
$re = $sftp->ssh2_dir_exits($remotePath);
if($re) {
    $fileArr = $sftp->fileList($remotePath);
    if(!$fileArr) {
                 printLog ('El directorio remoto no pudo obtener la lista de archivos o el directorio remoto está vacío'); salir;
    }
    foreach ($fileArr as $k => $v) {
        $downRes = $sftp->downSftp($remotePath.'/'.$v, $localPath.'\\'.$v);
        if(!$downRes) {
                         printLog ('Error al descargar el archivo ---'.$v);
        } else {
                         printLog ('Archivo descargado con éxito ---'.$v);
            sleep(1);
            $delRes = $sftp->deleteSftp($remotePath.'/'.$v);
            if(!$delRes) {
                                 printLog ('Error al eliminar el archivo ---'.$v);
            } else {
                                 printLog ('Eliminó exitosamente el archivo ---'.$v);
            }
        }
    }
    sleep(1);
    $upRes = $sftp->upSftp($localPath.'112233.html',$remotePath.'123.html');
    if(!$upRes) {
                 printLog ('Error al cargar el archivo --- 112233.html');
    } else {
                 printLog ('Archivo cargado con éxito --- 112233.html');
    }
} else {
         printLog ('El acceso al directorio remoto no existe'); salir;
}
 
class Sftp
{
         // La configuración inicial es NULL
    private $config = NULL;
         // La conexión es NULL
    private $conn = NULL;
    //sftp resource
    private $ressftp = NULL;
         // inicializar
    public function __construct($config)
    {
        $this->config = $config;
        $this->connect();
    }
 
    public function connect()
    {
        $this->conn = ssh2_connect($this->config['host'], $this->config['port']);
        if( ssh2_auth_password($this->conn, $this->config['username'], $this->config['password'])) {
                         $ this-> ressftp = ssh2_sftp ($ this-> conn); // Inicie el sistema de transmisión gravitacional
        }else{
                         printLog ("Nombre de usuario o contraseña incorrectos"); exit ();
        }
    }
 
    /**
           * Determine si existe el directorio remoto
           * @param $ dir / directorio remoto
     * @return bool
     */
    public function ssh2_dir_exits($dir){
        return file_exists("ssh2.sftp://".intval($this->ressftp).$dir);
    }
 
    /**
           * descargar archivo 
           * @param $ dirección de archivo remota / remota
           * @param $ local / descargar a la dirección local
     * @return bool
     */
    public function downSftp($remote, $local)
    {
        return copy("ssh2.sftp://".intval($this->ressftp).$remote,$local);
    }
 
    /**
           * Subir archivo 
           * @param $ dirección de archivo local / local
           * @param $ remote / dirección de archivo después de cargar
     * @param int $file_mode
     * @return bool
     */
    public function upSftp($local,$remote,$file_mode = 0777)
    {
        return copy($local, "ssh2.sftp://".intval($this->ressftp).$remote);
    }
 
    /**
           * Eliminar archivos en el directorio remoto
     * @param $file
     * @return bool
     */
    public function deleteSftp($file)
    {
        return ssh2_sftp_unlink($this->ressftp, $file);
    }
 
    /**
           * Recorrer directorio remoto
     * @param $remotePath
     * @return array
     */
    public function fileList($remotePath)
    {
        $fileArr = scandir('ssh2.sftp://'.intval($this->ressftp).$remotePath);
        foreach ($fileArr as $k => $v) {
            if($v == '.' || $v == '..') {
                unset($fileArr[$k]);
            }
        }
        return $fileArr;
    }
}
 
function printLog($log)
{
  echo '['.date('Y-m-d H:i:s',time()).']-'.$log."<br>";
}