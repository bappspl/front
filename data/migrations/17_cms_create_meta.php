<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateMeta extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_meta', array())
            ->addColumn('entity_id', 'integer')
            ->addColumn('entity_type', 'string')
            ->addColumn('title', 'string', array('null' => true))
            ->addColumn('keywords', 'text', array('null' => true))
            ->addColumn('description', 'text', array('null' => true))
            ->save();
    }

    public function down()
    {
        $this->dropTable('cms_meta');
    }
}