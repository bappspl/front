<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateBanner extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_banner', array())
            ->addColumn('status_id', 'integer')
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('url', 'string')
            ->addColumn('filename', 'text')
            ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();

        $this->createDirectory(array('files/banner'));
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
        $this->dropTable('cms_banner');
    }
}