<?php
function autoloader($className)
{
  //변수값에서 역슬래시를 슬래시로 교체.
  $fileName = str_replace('\\', '/', $className) . '.php';
  
  $file = __DIR__ . '/../classes/' . $fileName;
  
  include $file;  
}

spl_autoload_register('autoloader');