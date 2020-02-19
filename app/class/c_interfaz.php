<?php
interface c_interfaz
{
  /**
   * A�ade un registro
   *
   */
  public function add();
  /**
   * Actualiza un registro dependiendo de su identificador
   *
   */
  public function update($id);
  /**
   * Elimina un registro
   *
   */
  public function del($id);
  /**
   * Carga la informaci�n al objeto dependiendo de su identificador
   *
   */
  public function info($id);
  /**
   * Verifica si un dato existe en la base de datos
   *
   */
  public function exist();
  
  //public function existName($cad);
}
?>