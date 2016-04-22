<?php
namespace Nf;

class Config extends Singleton
{
    // use Helper\StoreTrait;
    
    protected static $_instance=null;
    protected static $data;
    
    public static function init($locale, $environment, $version) {
        $config = Ini::parse(Registry::get('applicationPath') . '/configs/config.ini', true, $locale . '-' . $environment . '-' . $version, 'common', true);
        self::$data = $config;
    }
    
    
      /*
    Get the value of a key
    For example, with Env, can be Config or Settings...):
    - getting the instance of this class_uses
        $env = \Nf\Env::getInstance();
        $value = $env->thing->key;
    - getting the instance through the static class :
        $value = \Nf\Env::get()->thing->key;
    */
    public static function get($key = null) {
        if($key === null) {
            return self::getInstance();
        }
        else {
            $instance = self::getInstance();
            return $instance->__get($key);    
        }
    }

    // helper function
    public function magicGet($key) {
        $data = self::$data;
        // if we ask for a nested variable (like db.my_site.password)
        if(strpos($key, '.')) {
            $explodedKey = explode('.', $key);
            $tempValue = $data;
            foreach($explodedKey as $k) {
                $tempValue = $tempValue->$k;
            }
            return $tempValue;
        }
        else {
            return $data->$key;
        }
    }
    
    
    public function __get($key) {
       return $this->magicGet($key);
    }
  
    public function __isset($key) {
        return isset(self::$data->$key);
    }
  
}