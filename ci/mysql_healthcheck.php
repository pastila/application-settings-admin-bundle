<?php
$servername = 'mysql';
$username = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
$database = getenv('MYSQL_DATABASE');

echo 'Waiting for database connection...', PHP_EOL;

for ($i=0; $i <= 50; $i++)
{
  try
  {
    $conn = new \PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    echo 'Connected successfully', PHP_EOL;
    break;
  }
  catch (\PDOException $e)
  {
    echo $e->getMessage(), PHP_EOL;
    echo 'Waiting for database connection...', PHP_EOL;
    sleep(5);
  }
}
?>