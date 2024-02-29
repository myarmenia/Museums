<?php

namespace App\Interfaces\Chat;

interface ChatInterface
{
  public function getSuperAdminRooms();

  public function getMuseumRooms($id);
  
  public function getRoomMessage($id);

  // public function getMuseumByUd($id);

  // public function getMuseumByLangAndId($id);

  // public function getApiMuseum();
  
}
