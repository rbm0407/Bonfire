<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Add User activation fields to the database
 */
class Migration_User_activations extends Migration
{
	/**
	 * @var string The name of the users table
	 */
	private $table = 'users';

	/**
	 * @var string The name of the settings table
	 */
	private $settings_table = 'settings';

	/**
	 * @var array Fields to add to the users table
	 */
	private $fields = array(
		'active' => array(
			'type'			=> 'tinyint',
			'constraint'	=> 1,
			'default'		=> '0',
		),
		'activate_hash' => array(
			'type'			=> 'VARCHAR',
			'constraint'	=> 40,
			'default'		=> '',
		),
	);

	/**
	 * @var array Data used to update the Users table
	 */
	private $data = array(
		'active' => 1,
	);

	/**
	 * @var array Data to insert into the settings table
	 */
	private $settings_data = array(
		'name' => 'auth.user_activation_method',
		'module' => 'core',
		'value' => '0',
	);

	/****************************************************************
	 * Migration methods
	 */
	/**
	 * Install this migration
	 */
	public function up()
	{
        $this->dbforge->add_column($this->table, $this->fields);

		$this->db->update($this->table, $this->data);

		$this->db->insert($this->settings_table, $this->settings_data);
	}

	/**
	 * Uninstall this migration
	 */
	public function down()
	{
		foreach ($this->fields as $column_name => $column_def)
		{
			$this->dbforge->drop_column($this->table, $column_name);
		}

		$this->db->where('name', $this->settings_data['name'])
			->delete($this->settings_table);
	}
}