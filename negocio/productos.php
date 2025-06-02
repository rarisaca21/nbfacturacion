<?php
	date_default_timezone_set('America/Lima');	 
	class Productos{  
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
                $rows = $this->db->query("SELECT * FROM productos ".$query. " ORDER BY id DESC LIMIT ".$i.", 10")->fetchAll(PDO::FETCH_ASSOC);
                $total = $this->db->query("SELECT COUNT(*) as total FROM productos ".$query)->fetchAll(PDO::FETCH_ASSOC);
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
                $this->db->insert("productos", [
                    "nombre" => $post['nombre'],
                    "descripcion" => $post['descripcion'],
                    "unidades_id" => $post['unidades_id'],
                    "codigo" => $post['codigo'],
                    "stock" => $post['stock'],
                    "precio" => $post['precio'],
                    "estado" => $post['estado']
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
                $data = $this->db->update("productos", [
                    "nombre" => $post['nombre'],
                    "descripcion" => $post['descripcion'],
                    "unidades_id" => $post['unidades_id'],
                    "codigo" => $post['codigo'],
                    "stock" => $post['stock'],
                    "precio" => $post['precio'],
                    "estado" => $post['estado']
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
                $rows = $this->db->query("SELECT * FROM productos WHERE id = " . $_POST['id'])->fetchAll(PDO::FETCH_ASSOC); 
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
                $res = $this->db->delete("productos", [
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