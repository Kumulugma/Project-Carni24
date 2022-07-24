<?php

if (!defined('ABSPATH'))
    exit;

/**
 *
 */
class K3eModules {

    public static function init() {
        self::loadModules();
    }

    public static function loadedModules() {
        $modules = scandir(K3E::PLUGIN_URL . "modules");
        $result = [];
        
        foreach ($modules as $module) {
            if (!in_array($module, ['.', '..', 'K3eModules.php'])) {
                if(class_exists($module)){
                    $result[] = ['name' => $module::NAME, 'class' => $module];
                }
            }
        }
        return $result;
    }
    
    public static function loadModules() {
        $modules = scandir(K3E::PLUGIN_URL . "modules");

        foreach ($modules as $module) {
            if (!in_array($module, ['.', '..', 'K3eModules.php'])) {
                require_once $module . '/' . $module . '.php';
                new $module();
            }
        }
    }

}
