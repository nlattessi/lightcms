<?php

use Phinx\Migration\AbstractMigration;

class CreateTableMarcas extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('marcas');
        $table
            ->addColumn('image_id', 'integer', ['null' => false, 'limit' => 11])
            ->addForeignKey('image_id', 'images', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addColumn('order', 'integer', ['null' => true])
            ->addColumn('name', 'string', ['null' => true])
            ->addColumn('caption', 'string', ['null' => true])
            ->addColumn('link', 'string', ['null' => true])
            ->addTimestamps()
            ->create();
    }
}
