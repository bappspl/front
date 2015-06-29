<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreatePlace extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_place', array())
            ->addColumn('name', 'string')
            ->addColumn('latitude', 'string')
            ->addColumn('longitude', 'string')
            ->addColumn('country', 'string', array('null' => true))
            ->addColumn('region', 'string', array('null' => true))
            ->addColumn('city', 'string', array('null' => true))
            ->addColumn('street', 'string', array('null' => true))
            ->addColumn('street_number', 'string', array('null' => true))
            ->save();
    }

    public function down()
    {
        $this->dropTable('cms_place');
    }
}