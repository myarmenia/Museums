<?php

namespace App\Interfaces\User;


interface StudentInfoInterface
{
    public function getUser(int $id); 
    public function getAll();
    public function getUserCourseLanguages(int $id);   

}
