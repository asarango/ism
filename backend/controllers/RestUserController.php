<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\controllers;

use yii\rest\ActiveController;

class RestUserController extends ActiveController
{
    //public $modelClass = 'app\models\User';
    public $modelClass = 'backend\models\Usuario';
}