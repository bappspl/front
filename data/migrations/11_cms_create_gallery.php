<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateGallery extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_gallery', array())
            ->addColumn('status_id', 'integer')
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('url', 'string')
            ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();

        $this->createDirectory(array('files/gallery'));

        $this->insertYamlValues('cms_gallery');
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

    public function createDirectory($dirs)
    {
        foreach($dirs as $dir)
        {
            $explodedDirs = explode('/', $dir);
            $parentDir = $explodedDirs[0];

            if(!is_dir('./public/'.$parentDir))
            {
                mkdir('./public/'.$parentDir);
            }

            if(!is_dir('./public/'.$dir))
            {
                mkdir('./public/'.$dir);
            }

            $files = glob('./public/'.$dir.'/*');
            foreach($files as $file)
            {
                if(is_file($file)) unlink($file);
            }
        }
    }

    public function down()
    {
        $this->dropTable('cms_gallery');
    }
}