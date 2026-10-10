<?php
// Load one DCL class under PHP 8.3 with DCL's autoload mapping (no DB, no bootstrap).
// usage: php8.3 class-load.php /path/to/dcl/dcl ClassName
error_reporting(E_ALL);
ini_set("display_errors", "1");
ini_set("html_errors", "0");
[$_, $root, $class] = $argv;
$root = rtrim($root, "/") . "/";
define("DCL_ROOT", $root);
define("DCL_WWW_ROOT", "/");

spl_autoload_register(function ($className) use ($root) {
    foreach (["inc/", "inc/lib/"] as $d) {
        if (file_exists($root . $d . $className . ".php")) { require_once $root . $d . $className . ".php"; return; }
    }
    $areas = ["Controller" => "controllers", "Model" => "models", "ViewData" => "models", "Presenter" => "presenters",
              "ModelValidator" => "validators", "Exception" => "exceptions", "Helper" => "helpers", "Service" => "services"];
    foreach ($areas as $suffix => $dir) {
        if (substr($className, -strlen($suffix)) === $suffix && file_exists($root . "inc/$dir/$className.php")) {
            require_once $root . "inc/$dir/$className.php"; return;
        }
    }
    $vendor = ["Smarty" => "vendor/Smarty/Smarty.class.php", "Valitron\\Validator" => "vendor/valitron/Validator.php"];
    if (isset($vendor[$className])) { require_once $root . $vendor[$className]; return; }
});

if (!function_exists("LoadStringResource")) { function LoadStringResource($n) {} }
require_once $root . "inc/lib/DbProvider.sqlsrv.php"; // the configured provider (dbType = sqlsrv) defines DbProvider
$deprecated = 0;
set_error_handler(function ($no, $str, $file, $line) use (&$deprecated) {
    if ($no & (E_DEPRECATED | E_USER_DEPRECATED)) { $deprecated++; echo "DEPRECATED: $str\n"; return true; }
    echo "WARNING/NOTICE: $str (", basename($file), ":$line)\n";
    return true;
});

try {
    if (!class_exists($class, true) && !interface_exists($class, true) && !trait_exists($class, true)) {
        echo "NOT-LOADED: $class\n";
        exit(2);
    }
    echo "OK: $class\n";
} catch (Throwable $e) {
    echo "EXCEPTION: ", get_class($e), ": ", $e->getMessage(), "\n";
    exit(3);
}
