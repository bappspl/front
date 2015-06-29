<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;
use Symfony\Component\Yaml\Yaml;

class CmsCreateUsers extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_users', array())
             ->addColumn('name', 'string')
             ->addColumn('surname', 'string')
             ->addColumn('password', 'string')
             ->addColumn('password_salt', 'string')
             ->addColumn('email', 'string')
             ->addColumn('email_confirmed', 'integer', array('limit' => 1))
             ->addColumn('role', 'integer' , array('limit' => 1))
             ->addColumn('active', 'integer' , array('limit' => 1))
             ->addColumn('filename', 'string' , array('null' => true))
             ->addColumn('registration_date', 'datetime')
             ->addColumn('registration_token', 'string')
             ->addColumn('extra', 'text', array('null' => true))
             ->save();

        $this->insertYamlValues('cms_users');
        $this->createDirectory(array('files/users'));
    }

    public function insertYamlValues($tableName)
    {
        $filename = './data/fixtures/'.$tableName.'.yml';
        $array = Yaml::parse(file_get_contents($filename));

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
        $this->dropTable('cms_users');
    }
}