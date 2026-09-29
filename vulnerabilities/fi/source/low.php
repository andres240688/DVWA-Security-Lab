<?php

// The page we wish to display
$file = $_GET[ 'page' ];

// 1. Definir lista blanca con únicamente los archivos permitidos
$allowed_pages = array(
    'include.php' => 'include.php',
    'file1.php'   => 'file1.php',
    'file2.php'   => 'file2.php',
    'file3.php'   => 'file3.php'
);

// 2. Validar si la página solicitada está en la lista blanca
if( !array_key_exists( $file, $allowed_pages ) ) {
    // Si no está permitida, se asigna una página por defecto inofensiva
    $file = 'include.php';
}

?>
