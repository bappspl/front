<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreatePage extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_page', array())
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('type', 'integer', array('null'=>true))
            ->addColumn('entity_type_id', 'integer')
            ->addColumn('status_id', 'integer')
            ->addColumn('filename_main', 'string', array('null'=>true))
            ->addColumn('filename_background', 'string', array('null'=>true))
            ->addColumn('position', 'integer', array('null'=>true))
            ->addColumn('removed', 'boolean', array('null'=>true))
            ->addColumn('date_creating', 'datetime', array('null'=>true))
            ->addColumn('date_editing', 'datetime', array('null'=>true))
            ->addColumn('date_removing', 'datetime', array('null'=>true))
            ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->addForeignKey('entity_type_id', 'cms_entity_type', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();

        $this->table('cms_page_part', array())
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('position', 'integer', array('null'=>true))
            ->addColumn('page_id', 'integer')
            ->addColumn('entity_type_id', 'integer')
            ->addColumn('filename_main', 'string', array('null'=>true))
            ->addColumn('removed', 'boolean', array('null'=>true))
            ->addColumn('date_creating', 'datetime', array('null'=>true))
            ->addColumn('date_editing', 'datetime', array('null'=>true))
            ->addColumn('date_removing', 'datetime', array('null'=>true))
            ->addForeignKey('page_id', 'cms_page', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->addForeignKey('entity_type_id', 'cms_entity_type', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();

        $this->createDirectory(array(
            'files/page',
            'temp_files/page',
            'files/page-part',
            'temp_files/page-part'
        ));
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
        $this->dropTable('cms_page');
        $this->dropTable('cms_page_part');
    }
}