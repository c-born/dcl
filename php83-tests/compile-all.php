<?php
// Compile every DCL template with a given Smarty into a throwaway compile dir (no DB, no live tree).
// usage: php8.3 compile-all.php /path/to/Smarty/libs /path/to/dcl/dcl [extra-plugin-dir]
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", "0");
[$_, $smartyDir, $dclRoot] = $argv;
$extra = $argv[3] ?? null;
$dclRoot = rtrim($dclRoot, "/") . "/";
require_once rtrim($smartyDir, "/") . "/Smarty.class.php";

$dep = [];
set_error_handler(function ($no, $str) use (&$dep) {
    if ($no & (E_DEPRECATED | E_USER_DEPRECATED)) {
        $k = preg_replace('/ in \/.*$/s', '', $str);
        $dep[$k] = ($dep[$k] ?? 0) + 1;
    }
    return true;
});

$s = new Smarty();
$s->setTemplateDir($dclRoot . "templates/");
$cd = sys_get_temp_dir() . "/smarty_compile_" . getmypid();
@mkdir($cd, 0700, true);
$s->setCompileDir($cd);
$plugins = [$dclRoot . "inc/plugins"];
if ($extra) $plugins[] = $extra;
$s->addPluginsDir($plugins);
foreach (["trim","is_numeric"] as $fn) $s->registerPlugin("modifier", $fn, $fn);
$s->error_reporting = E_ALL & ~E_NOTICE;
foreach (["Org","Contact","ViewData"] as $o) $s->registerObject($o, new stdClass()); // objects DCL registers at runtime
echo "Smarty ", Smarty::SMARTY_VERSION, " on PHP ", PHP_VERSION, "\n";

$ok = 0; $bad = [];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dclRoot . "templates/", FilesystemIterator::SKIP_DOTS));
foreach ($it as $f) {
    $p = $f->getPathname();
    if (substr($p, -4) !== ".tpl" || strpos($p, "templates_c") !== false) continue;
    $rel = substr($p, strlen($dclRoot . "templates/"));
    try {
        $tpl = $s->createTemplate($rel);
        $tpl->compileTemplateSource();
        $ok++;
    } catch (Throwable $e) {
        $bad[$rel] = get_class($e) . ": " . trim(preg_replace('/\s+/', ' ', $e->getMessage()));
    }
}
echo "compiled OK: $ok   failed: ", count($bad), "\n";
echo "--- failures:\n";
foreach ($bad as $rel => $m) echo "$rel => ", substr($m, 0, 420), "\n";
echo "--- deprecations (distinct, count):\n";
arsort($dep);
foreach (array_slice($dep, 0, 15, true) as $k => $n) echo $n, " x ", substr($k, 0, 190), "\n";
exec("rm -rf " . escapeshellarg($cd));
