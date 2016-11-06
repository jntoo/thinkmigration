<?php
/**
 * Created by PhpStorm.
 * User: JnToo
 * Date: 2016/10/28
 * Time: 1:41
 */
\think\Console::addDefaultCommands([
    "think\\laravel\\command\\Migrate",
    "think\\laravel\\command\\make\\Migration",
    "think\\laravel\\command\\migrate\\Rollback",
    "think\\laravel\\command\\migrate\\Reset",
    "think\\laravel\\command\\migrate\\Rollback",
]);
