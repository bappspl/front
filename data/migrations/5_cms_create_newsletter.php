<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateNewsletter extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_subscriber', array())
             ->addColumn('email', 'string')
             ->addColumn('first_name', 'string', array('null'=>true))
             ->addColumn('groups', 'string', array('null'=>true))
             ->addColumn('status_id', 'integer', array('null'=>true))
             ->addColumn('confirmation_code', 'string', array('null'=>true))
            ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();

        $this->table('cms_subscriber_group', array())
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('description', 'text', array('null'=>true))
            ->save();

        $this->table('cms_newsletter', array())
            ->addColumn('subject', 'string')
            ->addColumn('text', 'text')
            ->addColumn('groups', 'text', array('null'=>true))
            ->addColumn('status_id', 'integer')
            ->addForeignKey('status_id', 'cms_status', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
            ->save();

        $this->table('cms_newsletter_settings', array())
            ->addColumn('sender_email', 'string')
            ->addColumn('sender', 'string')
            ->addColumn('footer', 'text')
            ->save();

        $this->insertYamlValues('cms_newsletter_settings');
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

    public function down()
    {
        $this->dropTable('cms_subscriber');
        $this->dropTable('cms_subscriber_group');
        $this->dropTable('cms_newsletter');
        $this->dropTable('cms_newsletter_settings');
    }
}