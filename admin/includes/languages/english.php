<?php
    
    function lang($phrase){
        
        static $lang = array(
            
            //Dashboard words:
            'HOME'               => 'Home',
            'CATEGORIES'         => 'Categories',
            'ADMIN'              => 'Mahmoud',
            'EDIT_PROFILE'       => 'Edit profile',
            'SETTINGS'           => 'Settings',
            'LOGOUT'             => 'Logout',
            'ITEMS'              => 'Items',
            'MEMBERS'            => 'Members',
            'STATISTICS'         => 'Statistics',
            'LOGS'               => 'Logs'
        );
        
        return $lang[$phrase]; 
    }

    
      
