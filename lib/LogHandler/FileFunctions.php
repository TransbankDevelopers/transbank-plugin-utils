<?php

/**
 * Class FileFunctions
 *
 * @category
 * @package Transbank\PluginsUtils\LogHandler
 *
 */


namespace Transbank\PluginsUtils\LogHandler;


class FileFunctions
{

  var $directory;
  var $confDays;
  var $confWeight;
  var $lockFile;

  public function __construct($dir)
  {
    $this->directory = $dir;
  }

  private function createDir() {
    try {
      if (!file_exists($this->directory)) {
        mkdir($this->directory, 0777, true);
      }
    } catch(\Exception $e) {
      die($e);
    }
  }

  private function setLockFile() {
    if (!file_exists($this->lockFile)) {
      $file = fopen($this->lockFile, 'w') or die("No se puede crear archivo de bloqueo");
      $txt = "{$this->confDays}\n";
      $txt .= "{$this->confWeight}\n";
      fwrite($file, $txt);
      fclose($file);
      return true;
    } else {
      return false;
    }
  }

  public function digetsJob()
  {
    $files = glob($this->directory.'/*', GLOB_ONLYDIR);
    $deletions = array_slice($files, 0, count($files) - $this->confdays);
    foreach ($deletions as $to_delete) {
      array_map('unlink', glob("$to_delete"));
    }
  }

  public function backupFiles()
  {

  }
}
