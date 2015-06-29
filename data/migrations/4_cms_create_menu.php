<?php

use Phinx\Migration\AbstractMigration;

class CmsCreateMenu extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_menu_tree', array())
            ->addColumn('name', 'string')
            ->addColumn('machine_name', 'string')
            ->addColumn('position', 'integer')
            ->save();

        $this->insertYamlValues('cms_menu_tree');

        $this->table('cms_menu_node', array())
            ->addColumn('tree_id', 'integer')
            ->addColumn('parent_id', 'integer', array('null'=>true))
            ->addColumn('depth', 'integer')
            ->addColumn('is_visible', 'integer')
            ->addColumn('provider_type', 'string')
            ->addColumn('settings', 'text', array('null'=>true))
            ->addColumn('position', 'integer')
            ->addForeignKey('tree_id', 'cms_menu_tree', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->addForeignKey('parent_id', 'cms_menu_node', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();

        $this->insertYamlValues('cms_menu_node');

        $this->table('cms_menu_item', array())
            ->addColumn('node_id', 'integer')
            ->addColumn('label', 'string')
            ->addColumn('url', 'string')
            ->addColumn('position', 'integer')
            ->addColumn('subtitle', 'string', array('null'=>true))
            ->addForeignKey('node_id', 'cms_menu_node', 'id', array('delete' => 'NO_ACTION', 'update' => 'NO_ACTION'))
            ->save();

        $this->insertYamlValues('cms_menu_item');
    }

    public function insertYamlValues($tableName)
    {
        $filename = './data/fixtures/'.$tableName.'.yml';
        $array = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($filename));

        foreach ($array as $sArray){
            $value = '';

            foreach ($sArray as $kCol => $vCol) {
                $vCol === null ? $value = $value . $kCol .' = NULL , ' : $value = $value . $kCol .' = "' . $vCol . '", ';
            }

            $realValue = substr($value, 0, -2);
            $this->execute("SET NAMES UTF8");
            $this->adapter->execute('insert into '.$tableName.' set '.$realValue);
        }
    }

    public function down()
    {
        $this->dropTable('cms_menu_item');
        $this->dropTable('cms_menu_node');
        $this->dropTable('cms_menu_tree');
    }
}