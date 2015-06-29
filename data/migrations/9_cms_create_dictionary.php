<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateDictionary extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_dictionary', array())
             ->addColumn('name', 'string')
             ->addColumn('category', 'string')
             ->addColumn('website_id', 'integer', array('null'=>true))
             ->addColumn('filename', 'text', array('null'=>true))
             ->save();

        $this->createDirectory(array('files/dictionary'));
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
        $this->dropTable('cms_dictionary');
    }
}