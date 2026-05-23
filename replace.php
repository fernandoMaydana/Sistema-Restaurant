<?php
$dir = new RecursiveDirectoryIterator('c:/Users/Fernando/OneDrive/Desktop/htdocs/sistema-restaurante/resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);
$count = 0;
foreach($files as $f){
    $p = $f[0];
    $c = file_get_contents($p);
    
    // Replace ${{ number_format(...) }} with Bs {{ number_format(...) }}
    $nc = preg_replace('/\\$\\{\\{/', 'Bs {{', $c);
    
    // Replace $0.00 with Bs 0.00
    $nc = preg_replace('/\\$(?=[0-9])/', 'Bs ', $nc);
    
    // Replace -$ with -Bs
    $nc = str_replace('-$', '-Bs ', $nc);
    
    // Check if it's different
    if($c !== $nc){
        file_put_contents($p, $nc);
        $count++;
    }
}
echo "Modified $count files.";
