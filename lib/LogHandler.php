<?php

/**
 * Class LogHandler
 *
 * @category
 * @package Transbank\PluginsUtils
 *
 */


namespace Transbank\PluginsUtils;


// WA - import log4php
require_once ('../vendor/apache/log4php/src/main/php/Logger.php');

class LogHandler
{
    private $logFile;
    private $logDir;
    private $ecommerce;
    private $logLevel = array(
        'info' => true,
        'debug' => false,
        'error' => true,
    );
    private $lockFile;
    private $confDays;
    private $confWeight;
    private $configuration;
    private $logger;
    private $lockfile;

    public function __construct($ecommerce, $days = 0, $weight = '2MB', $info = false)
    {
        $base = '';
        if ($ecommerce == 'magento') {
            $this->logDir = BP . '/var/log/Transbank_Webpay';
            $this->lockFile = BP . '/var/lockfile.lock';
        } elseif ($ecommerce == 'prestashop') {
          $this->logDir = _PS_ROOT_DIR_."/var/logs/Transbank_webpay";
        } elseif ($ecommerce == 'woocommerce') {
          $this->logDir = ABSPATH . "log/Transbank_webpay";
        } elseif ($ecommerce == 'opencart') {
          $this->logDir = DIR_IMAGE."logs/Transbank_webpay";
        } elseif ($ecommerce == 'virtuemart'){
          $this->logDir = JPATH_ROOT . '/administrator/logs/Transbank_webpay';
        } else {
          $this->logDir = dirname(__DIR__).'/examples/logs';
        }

        if (!is_dir($this->logDir)){
          mkdir($this->logDir, 0777, true);
        }

        $this->logLevel['debug'] = $info;

        $day = date('Y-m-d');
        $this->ecommerce = $ecommerce;
        $this->confDays = $days;
        $this->confWeight = $weight;
        $this->logFile = $this->logDir.'/log_transbank_'.$this->ecommerce.'_'.$day.'.log';
        printf($this->logFile);
        $this->initLogger();
    }

    private function initLogger()
    {

      $this->configuration = array(
        'appenders' => array(
          'default' => array(
            'class' => 'LoggerAppenderRollingFile',
            'layout' => array(
              'class' => 'LoggerLayoutPattern',
              'params' => array(
                'conversionPattern' => '[%date{Y-m-d H:i:s}] %msg%n',
              )
            ),
            'params' => array(
              'file' => $this->logFile,
              'maxFileSize' => $this->confWeight,
              'maxBackupIndex' => 10,
            ),
          ),
        ),
        'rootLogger' => array(
          'appenders' => array('default'),
        ),
      );

      \Logger::configure($this->configuration);
      $this->logger = \Logger::getLogger('main');
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

  public function setparamsconf($days, $weight) {
    if (file_exists($this->lockfile)) {
      $file = fopen($this->lockfile, "w") or die("No se puede truncar archivo");
      if (! is_numeric($days) or $days == null or $days == '' or $days === false) {
        $days = 7;
      }
      $txt = "{$days}\n";
      fwrite($file, $txt);
      $txt = "{$weight}\n";
      fwrite($file, $txt);
      fclose($file);
      // chmod($this->lockfile, 0600);
    } else {
      //  echo "error!: no se ha podido renovar configuracion";
      exit;
    }
  }

    public function getValidateLockFile() {
        if (!file_exists($this->lockFile)) {
            $result = array(
                'status' => false,
                'lock_file' => basename($this->lockFile),
                'max_logs_days' => '7',
                'max_log_weight' => '2'
            );
        } else {
            $lines = file($this->lockFile);
            $this->confDays = trim(preg_replace('/\s\s+/', ' ', $lines[0]));
            $this->confWeight = trim(preg_replace('/\s\s+/', ' ', $lines[1]));
            $result = array(
                'status' => true,
                'lock_file' => basename($this->lockFile),
                'max_logs_days' => $this->confDays,
                'max_log_weight' => $this->confWeight
            );
        }
    }

    public function getLastLog()
    {
      $files = glob($this->logDir."/*.log");
      if (!$files) {
        return array("No existen Logs disponibles");
      }
      $files = array_combine($files, array_map("filemtime", $files));
      arsort($files);
      $this->lastLog = key($files);
      if (isset($this->lastLog)) {
        $var = file_get_contents($this->lastLog);
      } else {
        $var = null;
      }
      $return = array(
        'log_file' => basename($this->lastLog),
        'log_weight' => $this->formatBytes($this->lastLog),
        'log_regs_lines' => count(file($this->lastLog)),
        'log_content' => $var
      );
      return json_encode($return);
    }

    private function formatBytes($path) {
      $bytes = sprintf('%u', filesize($path));
      if ($bytes > 0) {
        $unit = intval(log($bytes, 1024));
        $units = array('B', 'KB', 'MB', 'GB');
        if (array_key_exists($unit, $units) === true) {
          return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
        }
      }
      return $bytes;
    }

    private function delLockFile() {
        if (file_exists($this->lockFile)) {
            unlink($this->lockFile);
        }
    }


    public function setLockStatus($status = true)
    {
        if ($status == true) {
            $this->setLockFile();
        } else {
            $this->delLockFile();
        }
    }

    public function setLogLevel($priority, $enable = true)
    {
        $this->logLevel[$priority] = $enable;
        return $this->logLevel;
    }


    public function logDebug($msg)
    {
        if ($this->logLevel['debug'])
        {
            $this->logger->debug('DEBUG: ' . $msg);
        }
    }

    public function logInfo($msg)
    {
        if ($this->logLevel['info'])
        {
            $this->logger->info('INFO: ' . $msg);
        }
    }

    public function logError($msg)
    {
        if ($this->logLevel['error'])
        {
            $this->logger->error('ERROR: ' . $msg);
        }
    }

}
