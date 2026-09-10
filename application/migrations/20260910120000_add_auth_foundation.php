<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_auth_foundation extends CI_Migration
{
    public function up()
    {
        $this->load->dbforge();

        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'username' => array('type' => 'VARCHAR', 'constraint' => 100),
            'email' => array('type' => 'VARCHAR', 'constraint' => 190),
            'password_hash' => array('type' => 'VARCHAR', 'constraint' => 255),
            'is_active' => array('type' => 'TINYINT', 'constraint' => 1, 'default' => 1),
            'created_at' => array('type' => 'DATETIME', 'null' => TRUE),
            'updated_at' => array('type' => 'DATETIME', 'null' => TRUE),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('username', FALSE, TRUE);
        $this->dbforge->add_key('email', FALSE, TRUE);
        $this->dbforge->create_table('users', TRUE, array('ENGINE' => 'InnoDB'));

        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'name' => array('type' => 'VARCHAR', 'constraint' => 100),
            'slug' => array('type' => 'VARCHAR', 'constraint' => 100),
            'created_at' => array('type' => 'DATETIME', 'null' => TRUE),
            'updated_at' => array('type' => 'DATETIME', 'null' => TRUE),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('slug', FALSE, TRUE);
        $this->dbforge->create_table('roles', TRUE, array('ENGINE' => 'InnoDB'));

        $this->dbforge->add_field(array(
            'id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'name' => array('type' => 'VARCHAR', 'constraint' => 100),
            'slug' => array('type' => 'VARCHAR', 'constraint' => 100),
            'created_at' => array('type' => 'DATETIME', 'null' => TRUE),
            'updated_at' => array('type' => 'DATETIME', 'null' => TRUE),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('slug', FALSE, TRUE);
        $this->dbforge->create_table('permissions', TRUE, array('ENGINE' => 'InnoDB'));

        $this->dbforge->add_field(array(
            'user_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'role_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'created_at' => array('type' => 'DATETIME', 'null' => TRUE),
        ));
        $this->dbforge->add_key(array('user_id', 'role_id'), TRUE);
        $this->dbforge->create_table('user_roles', TRUE, array('ENGINE' => 'InnoDB'));

        $this->dbforge->add_field(array(
            'role_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'permission_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE),
            'created_at' => array('type' => 'DATETIME', 'null' => TRUE),
        ));
        $this->dbforge->add_key(array('role_id', 'permission_id'), TRUE);
        $this->dbforge->create_table('role_permissions', TRUE, array('ENGINE' => 'InnoDB'));

        $this->db->query('ALTER TABLE user_roles ADD CONSTRAINT fk_user_roles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE user_roles ADD CONSTRAINT fk_user_roles_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE role_permissions ADD CONSTRAINT fk_role_permissions_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE role_permissions ADD CONSTRAINT fk_role_permissions_permission FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE');
    }

    public function down()
    {
        $this->load->dbforge();

        $this->dbforge->drop_table('role_permissions', TRUE);
        $this->dbforge->drop_table('user_roles', TRUE);
        $this->dbforge->drop_table('permissions', TRUE);
        $this->dbforge->drop_table('roles', TRUE);
        $this->dbforge->drop_table('users', TRUE);
    }
}
