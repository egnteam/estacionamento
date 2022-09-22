<?php

defined('BASEPATH') OR exit('Acão nao permitida');

/**  */

class Core_model extends CI_Model
{

	public function get_all($table = NULL, $condition = NULL)
	{

		if ($table && $this->db->table_exists($table)) {

			if (is_array($condition)) {
				$this->db->where($condition);
			}

			return $this->db->get($table)->result();
		} else {

			return FALSE;
		}

	}

	public function get_by_id($table = NULL, $condition = NULL)
	{

		if ($table && $this->db->table_exists($table) && is_array($condition)) {

			$this->db->where($condition);
			$this->db->limit(1);

			return $this->db->get($table)->row();
		} else {

			return FALSE;
		}

	}

	public function insert($table = NULL, $data = NULL)
	{

		if ($table && $this->db->table_exists($table) && is_array($data)) {

			$this->db->insert($table, $data);
			$this->db->limit(1);

			if ($this->db->affected_row() > 0) {

				$this->session->set_flashdata('sucesso', 'dados salvos com sucesso!');
			} else {

				$this->session->set_flashdata('error', 'não foi possivel salvar os dados');
			}

		} else {

			return false;

		}


	}

	public function update($table = NULL, $data = NULL, $condition = NULL)
	{
		if ($table && $this->db->table_exists($table) && is_array($data) && is_array($condition)) {
			if ($this->db->update($table, $data, $condition)) {

				$this->session->set_flashdata('sucesso', 'dados salvos com sucesso!');

			} else {

				$this->session->set_flashdata('error', 'não foi possivel salvar os dados!');
			}

		} else {
			return false;
		}

	}

	public function delete($table = NULL, $data = NULL, $condition = NULL)
	{

		if ($table && $this->db->table_exists($table) && is_array($condition)) {

			if ($this->db->delete($table, $condition)){

				$this->session->set_flashdata('sucesso','Registro excluido com sucesso');
			} else {

				$this->session->set_flashdata('error','Não foi posssivel excluit o registro');
			}

		} else {

			return false;
		}
	}
}
