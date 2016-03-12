<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateTag extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_tag', array())
             ->addColumn('name', 'string')
             ->addColumn('position', 'integer', array('null'=>true))
             ->save();

        $this->table('cms_tag_entity', array())
             ->addColumn('tag_id', 'integer')
             ->addColumn('entity_id', 'integer')
             ->addColumn('entity_type', 'string')
             ->addForeignKey('tag_id', 'cms_tag', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->save();
    }

    public function down()
    {
        $this->dropTable('cms_tag');
        $this->dropTable('cms_tag_entity');
    }
}