<?php

class DbalTest extends \PHPUnit_Framework_TestCase
{
  public static function setUpBeforeClass()
  {
    $schemaFile = __DIR__ . '/schema.sql';
    
    $cmd = 'mysql --login-path=tests < ' . $schemaFile;
    
    shell_exec($cmd);
  }
  public function getDbalConn()
  {
    $dbUrl = 'mysql://tests:tests@localhost/tests';
    
    $config = new \Doctrine\DBAL\Configuration();
    $connParams = 
    [
      'url' => $dbUrl,
      'driverOptions' => [\PDO::ATTR_EMULATE_PREPARES => false],
    ];
    $conn = \Doctrine\DBAL\DriverManager::getConnection($connParams, $config);
    return $conn;
  }
  public function getPdoConn()
  {
    $conn = new \PDO('mysql:dbname=tests;host=localhost','tests','tests');
    $conn->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE,\PDO::FETCH_ASSOC);
    
    return $conn;
  }
  public function testDbalIn()
  {
    $conn = $this->getDbalConn();
    
    $ids = [1,2,4];
    $rows = $conn->executeQuery(
        'SELECT * FROM users WHERE id IN(:ids);',
        ['ids' => $ids],
        ['ids' => \Doctrine\DBAL\Connection::PARAM_INT_ARRAY]
      )->fetchAll();
    
    $this->assertEquals(3,count($rows));
  }
  public function sestPdoIn()
  {
    $conn = $this->getPdoConn();
    
    $ids = [1,2,4];
    $stmt = $conn->prepare('SELECT * FROM users WHERE id IN(:ids);');
    $stmt->execute(['ids' => $ids]);
    $rows = $stmt->fetchAll();
    $this->assertEquals(3,count($rows));
  }
  public function testDbalTypes()
  {
    $conn = $this->getDbalConn();
    $rows = $conn->executeQuery('SELECT * FROM types;')->fetchAll();
    foreach($rows as $row)
    {
      foreach($row as $col => $value)
      {
        echo sprintf("Col %s %s %s\n",$col,$value,gettype($value));
      }
    }
    $this->assertEquals('integer',gettype($rows[0]['id']));
    $this->assertEquals('string', gettype($rows[0]['strx']));
    $this->assertEquals('integer',gettype($rows[0]['intx']));
    $this->assertEquals('integer',gettype($rows[0]['boolx']));   // Be nice if was boolean
    $this->assertEquals('string', gettype($rows[0]['decx']));    // Really should be some sort of float?
    $this->assertEquals('double', gettype($rows[0]['floatx']));  // Really should be float, rounding issues
    $this->assertEquals('double', gettype($rows[0]['doublex'])); // Really should be float
    
    $this->assertTrue($rows[0]['boolx'] === 1);
    $this->assertTrue($rows[1]['boolx'] === 0);
    /*
      Col id 2 integer
      Col strx TWO string
      Col intx 42 integer
      Col boolx 0 integer
      Col decx 5.22 string
      Col floatx 3.1415901184082 double
      Col doublex 3.14159 double
     */
    /*
mysql> describe types;
+---------+--------------+------+-----+---------+----------------+
| Field   | Type         | Null | Key | Default | Extra          |
+---------+--------------+------+-----+---------+----------------+
| id      | int(11)      | NO   | PRI | NULL    | auto_increment |
| strx    | varchar(255) | NO   |     | NULL    |                |
| intx    | int(11)      | YES  |     | NULL    |                |
| boolx   | tinyint(1)   | YES  |     | 0       |                |
| decx    | decimal(5,2) | YES  |     | NULL    |                |
| floatx  | float        | YES  |     | NULL    |                |
| doublex | double       | YES  |     | NULL    |                |
+---------+--------------+------+-----+---------+----------------+
     */
  }
  public function sestPdoTypes()
  {
    $conn = $this->getPdoConn();
    $rows = $conn->query('SELECT * FROM types;')->fetchAll();
    foreach($rows as $row)
    {
      foreach($row as $col => $value)
      {
        echo sprintf("Col %s %s %s\n",$col,$value,gettype($value));
      }
    }
    $this->assertTrue($rows[0]['boolx'] === 1);
    $this->assertTrue($rows[1]['boolx'] === 0);
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