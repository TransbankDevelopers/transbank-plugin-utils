<?php

/**
 * Class ReportPDFLog
 *
 * @category
 * @package Transbank\PluginsUtils
 *
 */


namespace Transbank\PluginsUtils;

use Transbank\PluginsUtils\LogHandler;
use Transbank\PluginsUtils\ReportPDF;

class ReportPDFLog
{

  private $document;
  private $ecommerce;

  public function __construct($doc, $ecommerce)
  {
    $this->document = $doc;
    $this->ecommerce = $ecommerce;
  }

  function getReport($json)
  {
    $log = new LogHandler($this->ecommerce);
    $data = json_decode($log->getLastLog());
    $obj = json_decode($json,true);
    $html = '';
    if (isset($data['log_content']) && $this->document == 'report'){
      $html = str_replace("\n","<br>",$data['log_content']);
      $text = explode ("<br>" ,$html);
      $html='';
      foreach ($text as $row){
        $html .= '<b>'.substr($row,0,21).'</b> '.substr($row,22).'<br>';
      }
      $obj += array('logs' => array('log' => $html));
    }
    $report = new ReportPDF();
    $report->getReport(json_encode($obj));
  }
}
