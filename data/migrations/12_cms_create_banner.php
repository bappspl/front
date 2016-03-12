<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateBanner extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_banner', array())
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('url', 'string')
            ->addColumn('status_id', 'integer')
            ->addColumn('entity_type_id', 'integer')
            ->addColumn('target', 'string', array('null' => true))
            ->addColumn('filename', 'text', array('null' => true))
            ->addColumn('position', 'integer', array('null'=>true))
            ->addColumn('removed', 'boolean', array('null'=>true))
            ->addColumn('date_creating', 'datetime', array('null'=>true))
            ->addColumn('date_editing', 'datetime', array('null'=>true))
            ->addColumn('date_removing', 'datetime', array('null'=>true))
            ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->addForeignKey('entity_type_id', 'cms_entity_type', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();

        $this->createDirectory(array('files/banner'));
    }

    public function createDirectory($dirs)
    {
        foreach($dirs as $dir) {
            $explodedDirs = explode('/', $dir);
            $parentDir = $explodedDirs[0];

            if(!is_dir('./public/'.$parentDir)) {
                mkdir('./public/'.$parentDir);
            }

            if(!is_dir('./public/'.$dir)) {
                mkdir('./public/'.$dir);
            }

            $files = glob('./public/'.$dir.'/*');

            foreach($files as $file) {
                if(is_file($file)) unlink($file);
            }
        }
    }

    public function down()
    {
        $this->dropTable('cms_banner');
    }
}