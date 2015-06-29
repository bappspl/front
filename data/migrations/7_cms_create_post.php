<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreatePost extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_post', array())
             ->addColumn('name', 'string')
             ->addColumn('url', 'string')
             ->addColumn('status_id', 'integer')
             ->addColumn('category', 'string', array('null'=>true))
             ->addColumn('text', 'text')
             ->addColumn('date', 'datetime', array('null'=>true))
             ->addColumn('author_id', 'integer')
             ->addColumn('filename_main', 'string', array('null'=>true))
             ->addColumn('extra', 'text', array('null'=>true))
             ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->addForeignKey('author_id', 'cms_users', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->save();

        $this->createDirectory(array('files/post', 'temp_files/post'));
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
        $this->dropTable('cms_post');
    }
}