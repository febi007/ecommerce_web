<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_crud extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}
	//user_guide/database/query_builder.html
	//$this->db->query('your query');
	//$query = $this->db->get('mytable');
	//$query = $this->db->get('mytable', 10, 20);
	//$this->db->select('*');c
	//$this->db->select_max/min/avg('title, content, date');
	//$this->db->from('blogs');
	//$this->db->join('comments', 'comments.id = blogs.id');
	//$this->db->join('comments', 'comments.id = blogs.id', 'left');
	//$where = array('name' => $name, 'title' => $title); default =, AND
	//$where = array('name !=' => $name, 'id <' => $id); default AND
	//$where = "name='Joe' AND status='boss' OR status='active'";
	//$this->db->where($where);
	//$names = array('Frank', 'Todd', 'James');
	//$this->db->where_in('username', $names);
	//$query = $this->db->get();
	//$this->db->order_by('title DESC, name ASC')

	public function my_query($my_query){
		$data = $this->db->query($my_query);
		if($data->num_rows()>0){
			foreach ($data->result_array() as $row);
			return $row;
		} else{
			return null;
		}
	}

	public function max_data($table, $field, $where=null){
		$this->db->select_max($field);
		$this->db->from($table);
		if($where != null){ $this->db->where($where); }
		$data = $this->db->get();
		foreach($data->result() as $row);
		$max = 0; if($row->$field > 0){ $max = $row->$field; }
		return $max;
	}

	public function count_data_join($table, $field, $table_join, $on, $where=null, $order=null, $group=null, $limit_sum=0, $limit_from=0, $having=null, $distinct=''){
		$col = explode('.', $field);
		if (count($col) > 1) {
			$alias = $col[1];
		} else {
			$alias = $field;
		}
		$this->db->select("COUNT(".$distinct." ".$field.") AS ".$alias."");
		$this->db->from($table);
		if(is_array($table_join) && is_array($on)){
			$i = 0;
			foreach($table_join as $row){
				if (is_array($row)) {
					$this->db->join($row['table'], $on[$i], $row['type']);
				} else {
					$this->db->join($row, $on[$i]);
				}
				$i++;
			}
		} else {
			$this->db->join($table_join, $on);
		}
		if($where != null){ $this->db->where($where); }
		if($order != null){ $this->db->order_by($order); }
		if($group != null){ $this->db->group_by($group); }
		if($having != null){ $this->db->having($having); }
		if($limit_sum != 0){ $this->db->limit($limit_sum, $limit_from); }
		$data = $this->db->get();
		foreach ($data->result_array() as $row);
		return $row[$alias];
	}

	public function count_data($table, $field, $where=null, $order=null, $group=null, $limit_sum=0, $limit_from=0, $having=null, $distinct=''){
		$col = explode('.', $field);
		if (count($col) > 1) {
			$alias = $col[1];
		} else {
			$alias = $field;
		}
		$this->db->select("COUNT(".$distinct." ".$field.") AS ".$alias."");
		$this->db->from($table);
		if($where != null){ $this->db->where($where); }
		if($order != null){ $this->db->order_by($order); }
		if($group != null){ $this->db->group_by($group); }
		if($having != null){ $this->db->having($having); }
		if($limit_sum != 0){ $this->db->limit($limit_sum, $limit_from); }
		$data = $this->db->get();
		foreach ($data->result_array() as $row);
		return $row[$alias];
	}

	public function count_join_data($table, $field, $table_join, $on, $where=null, $order=null, $group=null, $having=null){
		$this->db->select($field);
		$this->db->from($table);
		if(is_array($table_join) && is_array($on)){
			$i = 0;
			foreach($table_join as $row){
				if (is_array($row)) {
					$this->db->join($row['table'], $on[$i], $row['type']);
				} else {
					$this->db->join($row, $on[$i]);
				}
				$i++;
			}
		} else {
			$this->db->join($table_join, $on);
		}
		if($where != null){ $this->db->where($where); }
		if($order != null){ $this->db->order_by($order); }
		if($group != null){ $this->db->group_by($group); }
		if($having != null){ $this->db->having($having); }
		$data = $this->db->get();
		return $data->num_rows();
	}

	public function join_data($table, $field, $table_join, $on, $where=null, $order=null, $group=null, $limit_sum=0, $limit_from=0, $having=null){
		$this->db->select($field);
		$this->db->from($table);
		if(is_array($table_join) && is_array($on)){
			$i = 0;
			foreach($table_join as $row){
				if (is_array($row)) {
					$this->db->join($row['table'], $on[$i], $row['type']);
				} else {
					$this->db->join($row, $on[$i]);
				}
				$i++;
			}
		} else {
			$this->db->join($table_join, $on);
		}
		if($where != null){ $this->db->where($where); }
		if($order != null){ $this->db->order_by($order); }
		if($group != null){ $this->db->group_by($group); }
		if($having != null){ $this->db->having($having); }
		if($limit_sum != 0){ $this->db->limit($limit_sum, $limit_from); }
		$data = $this->db->get();
		return $data->result_array();
	}

	public function get_join_data($table, $field, $table_join, $on, $where=null, $order=null, $group=null, $limit_sum=0, $limit_from=0, $having=null){
		$this->db->select($field);
		$this->db->from($table);
		if(is_array($table_join) && is_array($on)){
			$i = 0;
			foreach($table_join as $row){
				if (is_array($row)) {
					$this->db->join($row['table'], $on[$i], $row['type']);
				} else {
					$this->db->join($row, $on[$i]);
				}
				$i++;
			}
		} else {
			$this->db->join($table_join, $on);
		}
		if($where != null){ $this->db->where($where); }
		if($order != null){ $this->db->order_by($order); }
		if($group != null){ $this->db->group_by($group); }
		if($having != null){ $this->db->having($having); }
		if($limit_sum != 0){ $this->db->limit($limit_sum, $limit_from); }
		$data = $this->db->get();

		if($data->num_rows()>0){
			foreach ($data->result_array() as $row);
			return $row;
		} else{
			return null;
		}
	}

	public function search_in_data($table, $field, $where, $in, $order=null, $limit_sum=0, $limit_from=0){
		$this->db->select($field);
		$this->db->where_in($where, $in);
		$this->db->from($table);
		if($order != null){ $this->db->order_by($order); }
		if($limit_sum != 0){ $this->db->limit($limit_sum, $limit_from); }
		$data = $this->db->get();
		return $data->result_array();
	}

	public function get_data($table, $field, $where=null, $order=null, $group=null, $limit_sum=0, $limit_from=0){
		$this->db->select($field);
		$this->db->from($table);
		if($where != null){ $this->db->where($where); }
		if($order != null){ $this->db->order_by($order); }
		if($group != null){ $this->db->group_by($group); }
		if($limit_sum != 0){ $this->db->limit($limit_sum, $limit_from); }
		$data = $this->db->get();
		if($data->num_rows()>0){
			foreach ($data->result_array() as $row);
			return $row;
		} else{
			return null;
		}
	}

	public function create_data($tabel, $data){
		$data = $this->db->insert($tabel, $data);
		return $data;
	}

	public function count_read_data($table, $field, $where=null, $order=null, $group=null, $having=null){
		$this->db->select($field);
		$this->db->from($table);
		if($where != null){ $this->db->where($where); }
		if($order != null){ $this->db->order_by($order); }
		if($group != null){ $this->db->group_by($group); }
		if($having != null){ $this->db->having($having); }
		$data = $this->db->get();
		return $data->num_rows();
	}

	public function read_data($table, $field, $where=null, $order=null, $group=null, $limit_sum=0, $limit_from=0, $having=null){
		$this->db->select($field);
		$this->db->from($table);
		if($where != null){ $this->db->where($where); }
		if($order != null){ $this->db->order_by($order); }
		if($group != null){ $this->db->group_by($group); }
		if($having != null){ $this->db->having($having); }
		if($limit_sum != 0){ $this->db->limit($limit_sum, $limit_from); }
		$data = $this->db->get();
		return $data->result_array();
	}

	public function update_data($tabel, $data, $where){
		$data = $this->db->update($tabel, $data, $where);
		return $data;
	}

	public function delete_data($tabel, $where){
		$data = $this->db->delete($tabel, $where);
		return $data;
	}

	public function check_data($column, $table, $condition) {
		$this->db->select($column);
		$this->db->from($table);
		$this->db->where($condition);

		return $this->db->get()->num_rows();
		/*if ($this->db->get()->row() != '') {
			return true;
		}else {
			return false;
		}*/
	}

	public function select_limit_join($table, $column, $table_join, $on, $condition=null, $order, $group=null, $limit_start, $limit_end, $having=null) {
		$join = '';
		if(is_array($table_join) && is_array($on)){
			$i = 0;
			foreach($table_join as $row){
				if (is_array($row)) {
					$join .= ' '.$row['type'].' JOIN '.$row['table'].' ON '.$on[$i].' ';
				} else {
					$join .= ' JOIN '.$row.' ON '.$on[$i].' ';
				}
				$i++;
			}
		} else {
			$join .= ' JOIN '.$table_join.' ON '.$on.' ';
		}

		$data = $this->db->query(
			"WITH PAGE AS 
			(
				SELECT DISTINCT ROW_NUMBER() OVER(ORDER BY ".$order.") AS ROW, ".$column."
				FROM ".$table." 
				".$join."
				".($condition==null?'':'WHERE '.$condition)."
				".($group==null?'':' GROUP BY '.$group)."
				".($having==null?'':' HAVING '.$having)."
			) 
            SELECT * FROM PAGE WHERE ROW BETWEEN ".$limit_start." AND ".$limit_end.""
		);

		return $data->result_array();
	}

	public function select_limit($table, $column, $condition=null, $order, $group=null, $limit_start, $limit_end, $having=null) {
		$data = $this->db->query(
			"WITH PAGE AS 
			(
				SELECT DISTINCT ROW_NUMBER() OVER(ORDER BY ".$order.") AS ROW, ".$column."
				FROM ".$table." 
				".($condition==null?'':'WHERE '.$condition)."
				".($group==null?'':' GROUP BY '.$group)."
				".($having==null?'':' HAVING '.$having)."
			) 
            SELECT * FROM PAGE WHERE ROW BETWEEN ".$limit_start." AND ".$limit_end.""
		);

		return $data->result_array();
	}

	public function select_union($col1,$tb1,$con1=null,$col2,$tb2,$con2=null,$order=null,$limit_from=null,$limit_to=null) {
		$this->db->select($col1);
		$this->db->from($tb1);
		if($con1 != null){ $this->db->where($con1); }
		$q1 = $this->db->get_compiled_select();

		$this->db->select($col2);
		$this->db->from($tb2);
		if($con2 != null){ $this->db->where($con2); }
		$q2 = $this->db->get_compiled_select();

		$q_order = ($order != null)?' ORDER BY '.$order:'';
		return $this->db->query(''.$q1.' UNION '.$q2.$q_order)->result_array();
	}

}

