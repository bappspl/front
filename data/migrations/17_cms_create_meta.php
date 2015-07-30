<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsCreateMeta extends AbstractMigration
{

    public function up()
    {
        $this->table('cms_meta', array())
            ->addColumn('entity_id', 'integer')
            ->addColumn('entity_type', 'string')
            ->addColumn('title', 'string', array('null' => true))
            ->addColumn('keywords', 'text', array('null' => true))
            ->addColumn('description', 'text', array('null' => true))

            ->addColumn('created', 'datetime', array('null' => true))
            ->addColumn('user_created', 'string', array('null' => true))
            ->addColumn('modified', 'datetime', array('null' => true))
            ->addColumn('user_modified', 'string', array('null' => true))
            ->save();

        $this->trigger();
    }

    public function trigger()
    {
        $this->adapter->execute('CREATE TRIGGER insert_meta BEFORE INSERT ON cms_meta
                                    FOR EACH ROW SET NEW.created = NOW(), NEW.user_created = USER();');
        $this->adapter->execute('CREATE TRIGGER update_meta BEFORE UPDATE ON cms_meta
                                    FOR EACH ROW SET NEW.modified = NOW(), NEW.user_modified = USER();');

        // triggers for log event
        $this->adapter->execute("DROP TRIGGER IF EXISTS insert_meta_log_event;
                                 CREATE TRIGGER insert_meta_log_event
                                 AFTER INSERT ON cms_meta
                                 FOR EACH ROW
                                 BEGIN
                                    INSERT INTO cms_log_event (entity_id, entity_type, what, action, description, user, date)
                                    SELECT NEW.id, 'Meta', 'utworzył metę', 'Insert', NEW.title, USER(), NOW()
                                        FROM cms_meta
                                        WHERE cms_meta.id = NEW.id;
                                 END;");

        $this->adapter->execute("DROP TRIGGER IF EXISTS update_meta_log_event;
                                 CREATE TRIGGER update_meta_log_event
                                 AFTER UPDATE ON cms_meta
                                 FOR EACH ROW
                                 BEGIN
                                    INSERT INTO cms_log_event (entity_id, entity_type, what, action, description, user, date)
                                    SELECT NEW.id, 'Meta', 'zmodyfikował metę', 'Update', NEW.title, USER(), NOW()
                                        FROM cms_meta
                                        WHERE cms_meta.id = NEW.id;
                                 END;");

        $this->adapter->execute("DROP TRIGGER IF EXISTS delete_meta_log_event;
                                 CREATE TRIGGER delete_meta_log_event
                                 BEFORE DELETE ON cms_meta
                                 FOR EACH ROW
                                 BEGIN
                                    INSERT INTO cms_log_event (entity_id, entity_type, what, action, description, user, date)
                                    SELECT OLD.id, 'Meta', 'usunął metę', 'Delete', OLD.title, USER(), NOW()
                                        FROM cms_meta
                                        WHERE cms_meta.id = OLD.id;
                                 END;");
    }

    public function down()
    {
        $this->dropTable('cms_meta');
    }
}