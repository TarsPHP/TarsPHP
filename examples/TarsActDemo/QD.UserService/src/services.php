<?php
/**
 * Created by PhpStorm.
 * User: liangchen
 * Date: 2018/2/24
 * Time: 下午2:51.
 */

// 以namespace的方式,在psr4的框架下对代码进行加载
return array(
    'home-api' => 'Server\protocol\QD\UserService\UserObj\UserServiceServant',
    'home-class' => '\Server\impl\UserServiceImpl',
);
