<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreatePage extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_page', array())
             ->addColumn('name', 'string')
             ->addColumn('subtitle', 'string', array('null'=>true))
             ->addColumn('slug', 'string')
             ->addColumn('url', 'string')
             ->addColumn('status_id', 'integer')
             ->addColumn('content', 'text', array('null'=>true))
             ->addColumn('filename_main', 'string', array('null'=>true))
             ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->save();

        $this->createDirectory(array('files/page', 'temp_files/page'));
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
        $this->dropTable('cms_page');
    }
}