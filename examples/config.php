<?

$base = '../lib';

foreach (scandir($base) as $filename){
    $file = $base .'/'. $filename;
    if (is_file($file)){
        require $file;
    }
}