<?php

use PHPUnit\Framework\TestCase;

final class ModelTest extends TestCase
{
//    public function testLogOut()
//    {
//        $model = new Model();
//        $this->assertTrue($model->logoutMe());
//    }

    public function testDatabaseConfigFile()
    {
        $dbConfigFile = fopen("./src/database.config", "r") or die("Unable to open file!");
        $dbConfigFileString =  fgets($dbConfigFile);
        $this->assertTrue($dbConfigFileString == "localhost:u429721638_isp:ilkesfjloieswkjfsdlkjfds:u429721638_isp");
    }

//    public function testSetSessionsToDefault()
//    {
//        $model = new Model();
//        $this->assertTrue($model->setDefaultSessions());
//    }

//    public function testSecureInputInjections()
//    {
//        $model = new Model();
//        $injection = "<script>(alert('hack'))</script>";
//        $injectionSecured = $model->secureInput($injection);
//        $this->assertTrue($injection != $injectionSecured);
//    }
}