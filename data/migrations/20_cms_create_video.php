<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateVideo extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_video', array())
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('url', 'string', array('null' => true))
            ->addColumn('status_id', 'integer')
            ->addColumn('position', 'integer', array('null' => true))
            ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();
    }

    public function down()
    {
        $this->dropTable('cms_video');
    }
}