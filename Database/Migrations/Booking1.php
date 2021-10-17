<?php

use app\Database\DatabaseMigrations;

class Booking1 extends DatabaseMigrations
{

    public function up()
    {
        $this->addSql("CREATE TABLE `booking` (`id` varchar(125) NOT NULL,`tripid` varchar(125) NOT NULL,`username` varchar(125) NOT NULL,`numofslots` int(11) NOT NULL,`bookingdate` datetime NOT NULL DEFAULT current_timestamp(),`updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->addSql("ALTER TABLE `booking` ADD PRIMARY KEY (`id`);");
    }

    public function down()
    {
        $this->addSql('DROP TABLE booking');
    }

}
