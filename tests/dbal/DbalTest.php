<?php

class DbalTest extends \PHPUnit_Framework_TestCase
{
  public static function setUpBeforeClass()
  {
    $schemaFile = __DIR__ . '/schema.sql';
    
    $cmd = 'mysql --login-path=tests < ' . $schemaFile;
    
    shell_exec($cmd);
  }
  public function test1()
  {
    $dbUrl = 'mysql://tests:tests@localhost/tests';
    
    $config = new \Doctrine\DBAL\Configuration();
    $connParams = 
    [
      'url' => $dbUrl,
      'driverOptions' => [\PDO::ATTR_EMULATE_PREPARES => false],
    ];
    $conn = \Doctrine\DBAL\DriverManager::getConnection($connParams, $config);
    
    $ids = [1,2,4];
    $rows = $conn->executeQuery(
        'SELECT * FROM users WHERE id IN(:ids);',
        ['ids' => $ids],
        ['ids' => \Doctrine\DBAL\Connection::PARAM_INT_ARRAY]
      )->fetchAll();
    
    $this->assertEquals(3,count($rows));
  }
  /*
   * "C:\home\ahundiak\zayso2015\tests\vendor\bin\phpunit.bat" 
   *   "--colors" 
   *   "--log-junit" "C:\Users\AHUNDI~1.IGS\AppData\Local\Temp\nb-phpunit-log.xml" 
   *   "--configuration" "C:\home\ahundiak\zayso2015\tests\phpunit.xml.dist" 
   *   "C:\Users\ahundiak.IGSLAN\AppData\Roaming\NetBeans\8.0\phpunit\NetBeansSuite.php" 
   *   "--run=C:\home\ahundiak\zayso2015\tests\tests"
   */
}