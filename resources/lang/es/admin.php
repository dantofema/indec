<?php

return [
    'admin-user' => [
        'title' => 'Usarios',

        'actions' => [
            'index' => 'Usuarios',
            'create' => 'Nuevo Usuario',
            'edit' => 'Editar :name',
            'edit_profile' => 'Editar Profile',
            'edit_password' => 'Editar Password',
        ],

        'columns' => [
            'id' => 'ID',
            'first_name' => 'Nombre',
            'last_name' => 'Apellido',
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Password Confirmation',
            'activated' => 'Activado',
            'forbidden' => 'No permitido',
            'language' => 'Idioma',
                
            //Belongs to many relations
            'roles' => 'Roles',
                
        ],
    ],

    // Do not delete me :) I'm used for auto-generation
];
