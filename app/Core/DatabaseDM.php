<?php
namespace App\Core;

use MongoDB\Client;

class DatabaseDM // Document Manager
{
    






private static $dmInstance = null;
    private $mongoClient;

    private function __construct()
    {
        try {
            if ($_SERVER['SERVER_NAME'] == 'localhost') {
                
                $username_password = (MDB_USER != '' && MDB_PASS !='') ? (MDB_USER . ':' . MDB_PASS . '@') : '';  //by default, no user/psw on localhost
                $host_port = MDB_HOST . ':' . MDB_PORT;
                $this->mongoClient = new Client(
                    uri: 'mongodb://' . $username_password . $host_port, 
                    uriOptions: [],              // authentication credentials or query string parameters
                    driverOptions: [         
                        'typeMap' => [           // default type map to apply to cursors, here turn everything into array
                            'array' => 'array',
                            'document' => 'array',
                            'root' => 'array'
                        ],
                    ]
                );

            }else{
                $uri = $_ENV['MDB_URI']?: throw new \RuntimeException(
                    'Set the MONGODB_URI environment variable to your Atlas URI'
                );
                //$uri="mongodb+srv://admin_S25:passSTU25@cluster0.gwnbz87.mongodb.net/";
                $this->mongoClient = new Client(
                    uri: $uri,
                    uriOptions: [],
                    driverOptions: [         
                        'typeMap' => [
                            'array' => 'array',
                            'document' => 'array',
                            'root' => 'array'
                        ],
                    ]
                );
            }            
        } catch (\Exception $e) {
        // Fails if the URL is malformed
        // Fails without a SVR check
        // fails if the IP is blocked by an ACL or firewall
        echo('[DATABASE ERROR] ' . $e->getMessage());
        exit('Une erreur est survenue. Merci de réessayer plus tard.');
        }
    }   

    /* Singleton design (one class = one(unique) object). If We already have a dm object, use it */
    public static function getDmInstance()
    {
        if (self::$dmInstance === null) {
            self::$dmInstance = new DatabaseDM();
        }
        return self::$dmInstance->mongoClient;
    }
    
}