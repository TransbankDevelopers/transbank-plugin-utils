<?php

/**
 * Class EcommerceInfo
 *
 * @category
 * @package Transbank\PluginsUtils\css
 *
 */


namespace Transbank\PluginsUtils\css;


class EcommerceInfo
{

  var $ecommerce;
  public function __construct($ecommerce)
  {
    $this->ecommerce = $ecommerce;
  }

  public function getEcommerceVersion(){
    switch ($this->ecommerce){
      case 'magento':
        $result = $this->getMagentoInfo();
        break;
    }
  }

  private function getLastGitHubReleaseVersion($string) {
    $baseurl = 'https://api.github.com/repos/'.$string.'/releases/latest';
    $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    $content=curl_exec($ch);
    curl_close($ch);
    $con = json_decode($content, true);
    $version = $con['tag_name'];
    return $version;
  }

  private function getMagentoInfo(){
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $productMetadata = $objectManager->get('Magento\Framework\App\ProductMetadataInterface');
    $actualversion = $productMetadata->getVersion();
    $lastversion = $this->getLastGitHubReleaseVersion('Magento/Magento2');
    $plugininfo = $objectManager->get('Magento\Framework\Module\ModuleList')->getOne('Transbank_Webpay');
    $currentplugin = $plugininfo['setup_version'];
    $result = array(
      'current_ecommerce_version' => $actualversion,
      'last_ecommerce_version' => $lastversion,
      'current_plugin_version' => $currentplugin
    );
    return $result;
  }

  private function getPluginInfo()
  {

  }
}
