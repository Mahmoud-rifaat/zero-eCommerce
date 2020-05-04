<?php
    
    function lang($phrase){
        
        static $lang = array(
            
            'MESSAGE' => 'ahla',
            'ADMIN'   => 'modeer',
        );
        
        return $lang[$phrase]; 
    }

    
      
