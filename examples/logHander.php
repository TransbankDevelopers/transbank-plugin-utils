<?
require_once ('config.php');

use Transbank\PluginsUtils\LogHandler;

$log = new LogHandler("test", 7, '2MB', true);

$log->logInfo('Mensaje de nivel INFO');
$log->logDebug('mensaje nivel debug');
$log->logError('mensaje de error');
$response = $log->getLastLog();
echo "<pre class='prettyprint lang-json'>".var_export($respose, true)."</pre>";

?>

<container>
  <hr>
  <h2 class="subtitle">Instanciar:</h2>
  <pre class="prettyprint lang-php">
    use Transbank\PluginsUtils\LogHandler;

    $ecommerce = ""; // Nombre comercio opc: magento, prestashop, virtuemart, opencart, woocommerce
    $days = 7; // numeros de dias de rotacion de log
    $weight= '2MB'; //Peso maximo de archivo de log, despues de superado se segmenta.
    $info= false; // nivel de info en logs activado o desactivado (opcional)

    $log = new LogHandler($ecommerce, $days, $weight, $info);
  </pre>

  types of logs
  <pre class="prettyprint lang-php">
    $log->logInfo('Mensaje de nivel INFO');
    $log->logDebug('mensaje nivel debug');
    $log->logError('mensaje de error');
  </pre>

  get last log
  <pre>
    $response  = $log->getLastLog();
    var_dump($response);
    <?php echo json_encode($response, JSON_PRETTY_PRINT); ?>
  </pre>

</container>

