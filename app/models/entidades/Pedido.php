<?php
class Pedido{
    private $id;
    private $id_usuario;
    private $data_pedido;
    private $total;
    private $estado;

    public function getId() { return $this->id; }
    public function getIdUsuario() { return $this->id_usuario; }
    public function getDataPedido() { return $this->data_pedido; }
    public function getTotal() { return $this->total; }
    public function getEstado() { return $this->estado; }

    public function setId($id) { $this->id = $id; }
    public function setIdUsuario($id_usuario) { $this->id_usuario = $id_usuario; }
    public function setDataPedido($data_pedido) { $this->data_pedido = $data_pedido; }
    public function setTotal($total) { $this->total = $total; }
    public function setEstado($estado) { $this->estado = $estado; }
}



?>