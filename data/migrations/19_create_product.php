<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateProduct extends AbstractMigration
{

    public function up()
    {
        $this->table('product', array())
             ->addColumn('name', 'string')
             ->addColumn('slug', 'string')
             ->addColumn('price', 'string', array('null'=>true))
             ->addColumn('catalog_number', 'string', array('null'=>true))
             ->addColumn('bestseller', 'string', array('null'=>true))
             ->addColumn('show_price', 'string', array('null'=>true))
             ->addColumn('category_id', 'integer', array('null'=>true))
             ->addColumn('status_id', 'integer')
             ->addColumn('class_id', 'integer', array('null'=>true))
             ->addColumn('length_id', 'integer', array('null'=>true))
             ->addColumn('height_id', 'integer', array('null'=>true))
             ->addColumn('width_id', 'integer', array('null'=>true))
             ->addColumn('volume_id', 'integer', array('null'=>true))
             ->addColumn('weight_id', 'integer', array('null'=>true))
             ->addColumn('unit_id', 'integer', array('null'=>true))
             ->addForeignKey('category_id', 'cms_category', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->addForeignKey('class_id', 'cms_dictionary', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->addForeignKey('length_id', 'cms_dictionary', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->addForeignKey('height_id', 'cms_dictionary', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->addForeignKey('width_id', 'cms_dictionary', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->addForeignKey('volume_id', 'cms_dictionary', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->addForeignKey('weight_id', 'cms_dictionary', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->addForeignKey('unit_id', 'cms_dictionary', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
             ->save();

        $this->createDirectory(array('files/product', 'temp_files/product'));
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
        $this->dropTable('product');
    }
}