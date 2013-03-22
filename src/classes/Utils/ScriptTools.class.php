<?php
define("EXTENSION_STOP", 'off');
class ScriptTools {
	private $locker = null;
	private $script = null;
	private $suffix = '';
	private $directoryLockFiles = '';
	private $directoryStopFiles = '';
	
	public function __construct($_scriptName, $suffix = '') {
        $this->script = substr($_scriptName, strlen(dirname($_scriptName).'/'));
        $this->directoryLockFiles = 'script-file-locking/';
        $this->directoryStopFiles = 'script-file-stopped/';
        
        $this->locker = new MyFileLocker($this->directoryLockFiles);
        
        if ($suffix !== '') {
            $suffix = '_' . $suffix;
        }        
        $this->suffix = $suffix;
        
    }
	
    // generate lock file name
	private function genLockFileName($type, $params) {
		$lock_file="";
		if (is_array($params)) {
			reset($params);
			while( list($k, $v) = each($params) ) {
				$lock_file.="_".$v;
			}
			$lock_file = substr($lock_file,1);
		} else {
			$lock_file = $params;
		}
		$lock_file = $type.".".$lock_file.".lock";
		return $lock_file;
	}
     	
  	/**
     * Формирует имя лок-файла (без расширения и номера копии)
     */
    private function lockFileName() {
        return preg_replace(array("/^\.scripts\//", "/\//"), array('', '_'), $this->script) . $this->suffix;
    }
    	
	/**
     * Лочит файл  
     * @param int $maxCopies 
     * @return int номер копии (0, если в режиме одной копии)
     * @throw Exception
     */	
	public function lockFile($maxCopies) {
		$lockFileName = $this->lockFileName() ;
        $copy = $maxCopies>0 ? 0 : -1;
        
        do {
            $copy++;
            
            $filename = $this->genLockFileName($lockFileName, $copy);
            if ($this->locker->get($filename)) {
            	return $copy;
            }         
        } while($copy < $maxCopies);
        
        throw new Exception("Can't lock file. " . ($maxCopies>0 ? ($maxCopies . ' copies already running') : ''));
	}
	
	/**
	 * Готов к остановке.
	 */
	public function isReadyToStop() {
		$filename = $this->script.'.'.EXTENSION_STOP;
		if (file_exists(ONPHP_TEMP_PATH. $this->directoryStopFiles.$filename)) {
			return true;
		}
		return false;
	}
	
	/**
	 * Останавливает выполнение скрипта
	 * @return resource|boolean
	 */
	public function stop() {
		$filename = substr($this->script, 0, strrpos($this->script, '_stop')).'.php.'.EXTENSION_STOP;
		if (!file_exists(ONPHP_TEMP_PATH. $this->directoryStopFiles.$filename)) {
			if (!file_exists(ONPHP_TEMP_PATH. $this->directoryStopFiles)) {
		       mkdir(ONPHP_TEMP_PATH. $this->directoryStopFiles, 0700);
		    }
			return fopen(ONPHP_TEMP_PATH. $this->directoryStopFiles.$filename, 'w+');
		}
		return false;
	}
	
	/**
	 * "Запускает" скрипт
	 * @return resource|boolean
	 */
	public function start() {
		$filename = substr($this->script, 0, strrpos($this->script, '_start')).'.php.'.EXTENSION_STOP;
		if (file_exists(ONPHP_TEMP_PATH. $this->directoryStopFiles.$filename)) {
			return unlink(ONPHP_TEMP_PATH. $this->directoryStopFiles.$filename);			
		}
		return false;
	}
	
	/**
	 * Удаляем лок файлв
	 * @param int $currentCopy
	 */
	public function delLockFile($currentCopy) {
		$lockFileName = $this->lockFileName();
		$filename = $this->genLockFileName($lockFileName, $currentCopy);
		return $this->locker->drop($filename);
	}
	
}
