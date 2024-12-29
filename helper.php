<?php

/**
 * Get the base/absolute path
 * 
 * @param string $path
 * @return string
 */

function basePath($path = '')
{
    return __DIR__ . '/' . $path;
}

/**
 * Summary of loadView
 * @param string $name
 * @return void
 */
function loadView(string $name, array $data = []): void
{
    $path = basePath("App/views/{$name}.view.php");
    if (!file_exists($path)) {
        echo "view $name not found";
        return;
    }
    extract($data);
    require $path;

}

/**
 * Summary of loadPartials
 * @param string $name
 * @return void
 */
function loadPartials(string $name): void
{
    $path = basePath("App/views/partials/{$name}.view.php");
    if (!file_exists($path)) {
        echo "view $name not found";
        return;
    }
    require $path;
}

/**
 * inspect and die
 * @param mixed $value
 * @return void
 */
function dd(...$variable): void
{
    echo "<pre>";
    foreach ($variable as $key => $value) {
        var_dump($value);
    }
    echo "</pre>";
    die;
}

/**
 * Summary of formatNumber
 * @param string $number
 * @return string
 */
function formatNumber(string $number): string
{
    return "$ " . number_format($number, 2);
}

function sanitize(string $dirty): string
{
    return filter_var($dirty, FILTER_SANITIZE_SPECIAL_CHARS);
}

function redirect(string $uri): never
{
    header("Location: $uri");
    exit;
}