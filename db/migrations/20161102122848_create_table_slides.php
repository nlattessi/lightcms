<?php

use Phinx\Migration\AbstractMigration;

class CreateTableSlides extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('slides');
        $table
            ->addColumn('image_id', 'integer', ['null' => false, 'limit' => 11])
            ->addForeignKey('image_id', 'images', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addColumn('order', 'integer', ['null' => true])
            ->addColumn('name', 'string', ['null' => true])
            ->addColumn('caption', 'string', ['null' => true])
            ->addTimestamps()
            ->create();
    }
}
