<?php

use app\Database\DatabaseMigrations;

class Trip1 extends DatabaseMigrations
{

    public function up()
    {
        $this->addSql("CREATE TABLE `trip` (`id` varchar(125) NOT NULL,`name` varchar(125) NOT NULL,`totalslots` int(11) NOT NULL,`availableslots` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->addSql("ALTER TABLE `trip` ADD PRIMARY KEY (`id`);");
    }

    public function down()
    {
        $this->addSql("DROP TABLE trip");
    }

}
