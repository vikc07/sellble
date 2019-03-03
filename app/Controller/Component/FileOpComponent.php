<?php

App::uses('Component', 'Controller');

class FileOpComponent extends Component{
	private $connected = false;
    protected function ftpConnect(){
        if(!$this->connected){
            $this->connected = ftp_connect(Configure::read('ftp.connInfo.host'));
            if(!$this->connected or !ftp_login($this->connected,Configure::read('ftp.connInfo.username'),Configure::read('ftp.connInfo.password'))){
                return false;
            }
        }

        return true;
    }

    public function uploadFile($args){
        if(!isset($args['forceLocal'])){
            $args['forceLocal'] = false;
        }

        $pathRoot = 'pathImg';

        if(isset($args['nonImg']) and $args['nonImg']){
            $pathRoot = 'pathNonImg';
        }

        if($args['forceLocal']){
            $args['saveFolder'] = Configure::read('local.' . $pathRoot) . $args['saveFolder'];
            $args['saveFolder'] = str_replace('/', Configure::read('local.directorySeparator'), $args['saveFolder']);
            $destFile = $args['saveFolder'] . Configure::read('local.directorySeparator')  . $args['destFileName'];
        }
        else{
            $args['saveFolder'] = Configure::read($pathRoot) . $args['saveFolder'];
            $args['saveFolder'] = str_replace('/', Configure::read('directorySeparator'), $args['saveFolder']);
            $destFile = $args['saveFolder'] . Configure::read('directorySeparator')  . $args['destFileName'];
        }

		if(is_uploaded_file($args['tempFileName'])){
			// Is FTP storage enabled?
			if(Configure::read('useFtpForStorage') and !$args['forceLocal']){
                if(!$this->ftpConnect()){
                    return false;
                }

                if(!@ftp_chdir($this->connected,$args['saveFolder'])){
                    if(!@ftp_mkdir($this->connected,$args['saveFolder'])){
                        return false;
                    }
                }

                ftp_pasv($this->connected, true);
                if(ftp_put($this->connected, $destFile, $args['tempFileName'], FTP_BINARY)){
                    return true;
                }
                else{
                    return false;
                }
			}
			else{
                if(!file_exists($args['saveFolder'])){
                    mkdir($args['saveFolder']);
                }
				return move_uploaded_file($args['tempFileName'], $destFile);
			}
    	}
		else{
			return false;
		}
    }

    public function deleteFile($args){
        if(!isset($args['forceLocal'])){
            $args['forceLocal'] = false;
        }

        $pathRoot = 'pathImg';

        if(isset($args['nonImg']) and $args['nonImg']){
            $pathRoot = 'pathNonImg';
        }

        if($args['forceLocal']){
            $args['folderName'] = Configure::read('local.' . $pathRoot) . $args['folderName'];
            $destFile = $args['folderName'] . Configure::read('local.directorySeparator')  . $args['fileName'];
        }
        else{
            $args['folderName'] = Configure::read($pathRoot) . $args['folderName'];
            $destFile = $args['folderName'] . Configure::read('directorySeparator')  . $args['fileName'];
        }

        if(Configure::read('useFtpForStorage') and !$args['forceLocal']){
            if(!$this->ftpConnect()){
                return false;
            }

            return ftp_delete($this->connected, $destFile);
        }
        else{
            if(file_exists($destFile)){
                return unlink($destFile);
            }
        }
    }
}

?>