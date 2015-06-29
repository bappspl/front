<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateBackendMenu extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_backend_menu', array())
             ->addColumn('name', 'string')
             ->addColumn('label', 'string')
             ->addColumn('route', 'text')
             ->addColumn('class', 'string', array('null'=>true))
             ->addColumn('access', 'integer', array('null'=>true))
             ->addColumn('visible_in_primary', 'string', array('null'=>true))
             ->addColumn('parent_id', 'integer', array('null'=>true))
             ->addColumn('params', 'text', array('null'=>true))
             ->addColumn('website_id', 'integer', array('null'=>true))
             ->save();

        $this->insertYamlValues('cms_backend_menu');
    }

    public function insertYamlValues($tableName)
    {
        $filename = './data/fixtures/'.$tableName.'.yml';
        $array = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($filename));

        foreach ($array as $sArray){
            $value = '';
            if(array_key_exists('parent_id', $sArray))
            {
                $parentKey = $sArray['parent_id'];
                $parentArray = $array[$parentKey];
                $name = $parentArray['name'];
                $route = $parentArray['route'];
                $parentId = $this->adapter->fetchRow('SELECT id FROM '.$tableName.' WHERE name="'.$name.'" AND route="'.$route.'" ');

                if($parentId)
                {
                    $sArray['parent_id'] = $parentId['id'];
                }
            }

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
        $this->dropTable('cms_backend_menu');
    }
}