<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateFile extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_file', array())
            ->addColumn('entity_id', 'integer', array('null' => true))
            ->addColumn('entity_type', 'string')
            ->addColumn('filename', 'text')
            ->addColumn('mime_type', 'string')
            ->save();
    }

    public function down()
    {
        $this->dropTable('cms_file');
    }
}