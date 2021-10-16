<?php

use app\Database\DatabaseMigrations;

class Cancellation1 extends DatabaseMigrations
{
    public function up()
    {
        $this->addSql("CREATE TABLE cancellation (`id` varchar(125) NOT NULL,  `bookingid` varchar(125) NOT NULL,  `cancelled` int(11) NOT NULL, `canceldate` datetime NOT NULL DEFAULT current_timestamp()) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->addSql("ALTER TABLE cancellation ADD PRIMARY KEY (`id`);");
    }

    public function down()
    {
        $this->addSql("DROP TABLE cancellation");
    }
}
