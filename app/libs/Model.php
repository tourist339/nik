<?php


class Model extends PDO
{
public function __construct($dsn, $username = null, $passwd = null, $options = null)
{
    parent::__construct($dsn, $username, $passwd, $options);
}
}