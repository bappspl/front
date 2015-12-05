<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateDisc extends AbstractMigration
{

    public function up()
    {
        $this->table('disc', array())
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('title', 'string', array('null'=>true))
            ->addColumn('content_first', 'text', array('null'=>true))
            ->addColumn('content_second', 'text', array('null'=>true))
            ->addColumn('filename_main', 'text', array('null'=>true))
            ->addColumn('year', 'string', array('null'=>true))
            ->addColumn('album', 'string', array('null'=>true))
            ->addColumn('facebook', 'string', array('null'=>true))
            ->addColumn('position', 'integer', array('null'=>true))
            ->addColumn('status_id', 'integer')
            ->addColumn('category_id', 'integer', array('null' => true))
            ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();

        $this->table('record', array())
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('title', 'string', array('null'=>true))
            ->addColumn('content', 'text', array('null'=>true))
            ->addColumn('listen', 'text', array('null'=>true))
            ->addColumn('buy', 'text', array('null'=>true))
            ->addColumn('status_id', 'integer')
            ->addColumn('disc_id', 'integer')
            ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->addForeignKey('disc_id', 'disc', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();

        $this->createDirectory(array('files/disc', 'temp_files/disc'));

//        $this->insertYamlValues('cms_gallery');
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
        $this->dropTable('disc');
        $this->dropTable('record');
    }
}