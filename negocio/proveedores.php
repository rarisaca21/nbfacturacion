<?php
	date_default_timezone_set('America/Lima');	 
	class Proveedores{  
        private $answer;
        private $db;
        function __construct($database){ 
            @session_start();
            $this->db = $database;
            $this->answer['err']['status'] = 0;
            $this->answer['err']['msg'] = '';
        }
        function __destruct(){            
        }
        public function board($post){
            try{
                $query = '';
                if(!empty($post['nombre'])){ $query = " WHERE nombre LIKE '%".$post['nombre']."%'"; }
                $i = (($post['current'] - 1) * 10);
                $f = $i + 10;
                $rows = $this->db->query("SELECT * FROM proveedores ".$query. " ORDER BY id DESC LIMIT ".$i.", 10")->fetchAll(PDO::FETCH_ASSOC);
                $total = $this->db->query("SELECT COUNT(*) as total FROM proveedores ".$query)->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e){
                $this->answer['err']['status'] = 1;
                $this->answer['err']['msg'] = $e->getMessage();
                return json_encode($this->answer);
            }
            $this->answer['data']['rows'] = $rows;
            $this->answer['data']['total'] = $total[0]['total'];
            return json_encode($this->answer);
        }
        public function add($post){
            try{
                $this->db->insert("proveedores", [
                    "nombre" => $post['nombre'],
                    "tipodocumentos_id" => $post['tipodocumentos_id'],
                    "documento" => $post['documento'],
                    "direccion" => $post['direccion'],
                    "email" => $post['email'],
                    "telefono" => $post['telefono']
                ]);
                $id = $this->db->id();
            } catch (PDOException $e){
                $this->answer['err']['status'] = 1;
                $this->answer['err']['msg'] = $e->getMessage();
                return json_encode($this->answer);
            } 
            $this->answer['data']['id'] = $id;
            return json_encode($this->answer);
        }
        public function update($post){
            try{
                $data = $this->db->update("proveedores", [
                    "nombre" => $post['nombre'],
                    "tipodocumentos_id" => $post['tipodocumentos_id'],
                    "documento" => $post['documento'],
                    "direccion" => $post['direccion'],
                    "email" => $post['email'],
                    "telefono" => $post['telefono']
                ], [
                    "id[=]" => $post['id']
                ]);
                $rows = $data->rowCount();
            } catch (PDOException $e){
                $this->answer['err']['status'] = 1;
                $this->answer['err']['msg'] = $e->getMessage();
                return json_encode($this->answer);
            } 
            $this->answer['data']['rowsAffected'] = $rows;
            return json_encode($this->answer);
        }
        public function searchById($post){
            try{
                $rows = $this->db->query("SELECT * FROM proveedores WHERE id = " . $_POST['id'])->fetchAll(PDO::FETCH_ASSOC); 
            } catch (PDOException $e){
                $this->answer['err']['status'] = 1;
                $this->answer['err']['msg'] = $e->getMessage();
                return json_encode($this->answer);
            } 
            $this->answer['data']['rows'] = $rows;
            return json_encode($this->answer);
        }
        public function deleteById($post){
            try{
                $res = $this->db->delete("proveedores", [
                    "id[=]" => $_POST['id']
                ]); 
                $rows = $res->rowCount();
            } catch (PDOException $e){
                $this->answer['err']['status'] = 1;
                $this->answer['err']['msg'] = $e->getMessage();
                return json_encode($this->answer);
            } 
            $this->answer['data']['rowsAffected'] = $rows;
            return json_encode($this->answer);
        }
    }
?>