<?php
function old(string $key, $default = '') {
    return $_POST[$key] ?? $default;
}