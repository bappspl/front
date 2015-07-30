<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;
use Symfony\Component\Yaml\Yaml;

class CmsCreateLogEvent extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_log_event', array())
             ->addColumn('entity_id', 'integer')
             ->addColumn('entity_type', 'string')
             ->addColumn('what', 'string')
             ->addColumn('action', 'string')
             ->addColumn('description', 'string')
             ->addColumn('user', 'string')
             ->addColumn('date', 'datetime')
             ->addColumn('viewed', 'integer', array('default' => 0))
             ->save();
    }

    public function down()
    {
        $this->dropTable('cms_log_event');
    }
}