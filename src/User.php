<?php
namespace linzening\devtools;
/**
 * 一级类
 */
use linzening\devtools\Demo\GetLage;
class User
{
    // test v1
    public function ret(){
        return GetLage::getInfo();
    }
}
