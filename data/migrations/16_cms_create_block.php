<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateBlock extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_block', array())

            ->addColumn('entity_id', 'integer')
            ->addColumn('entity_type_id', 'integer')
            ->addColumn('language_id', 'integer')
            ->addColumn('name', 'string')
            ->addColumn('value', 'text')
            ->addForeignKey('language_id', 'cms_language', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->addForeignKey('entity_type_id', 'cms_entity_type', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();
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
        $this->dropTable('cms_block');
    }
}