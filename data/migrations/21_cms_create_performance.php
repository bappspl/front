<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreatePerformance extends AbstractMigration
{

    public function up()
    {
        $this->table('performance', array())
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('url', 'string', array('null' => true))
            ->addColumn('content', 'text', array('null' => true))
            ->addColumn('enter', 'integer')
            ->addColumn('status_id', 'integer')
            ->addColumn('date', 'datetime', array('null'=>true))
            ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();

    }

    public function down()
    {
        $this->dropTable('performance');
    }
}