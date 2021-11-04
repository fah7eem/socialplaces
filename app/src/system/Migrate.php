<?php
namespace System;
use Monolog\Logger;
use Monolog\Handler\DBLogger;
class Migrate {

    public $db;
    protected $logger;
    function __construct() {
        try{
        $this->db = new \MeekroDB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT, CHARSET);
        $this->logger = new Logger('migrate');
        $this->logger->pushHandler(new DBLogger("app_logs",Logger::INFO));
        }catch(\Exception $e){
            echo "app_logs table failure";
        }
      }

    public function version(){
        return  $this->db->queryFirstField("select version from migrate order by file_name desc");
    }

    public function create_table(){
        if(!in_array("migrate",$this->db->tableList())){
            $this->db->query("CREATE TABLE IF NOT EXISTS `migrate` (
                `file_name` varchar(25) NOT NULL,
                `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                `version` varchar(10) DEFAULT NULL,
                PRIMARY KEY (`file_name`),
                UNIQUE KEY `file_name_UNIQUE` (`file_name`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            echo "\e[1m\e[35mMigration Table Created".PHP_EOL;
        }else{
            echo "\e[1mMigration Table Exist".PHP_EOL;
        }
    }

    private function run_file($file){
        $db = new \PDO("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
        $query = file_get_contents(MIGRATE_PATH.$file);
        $stmt = $db->prepare($query);
        if ($stmt->execute()){
            echo "\e[32m>".$file.PHP_EOL;
            $this->logger->info("Success: ".$file);
        }else{
            $this->delete_migrate_path();
            echo "\e[31mFail to execute: ".$file.PHP_EOL;
            throw NEW \Exception("Fail to execute: ".$file.PHP_EOL);
        }
    }

    private function delete_migrate_path(){
        if(MIGRATE_PATH_DELETE === true){
            echo "Deleting DB Migration Scripts".PHP_EOL;
            $files = array_diff(scandir(MIGRATE_PATH), array('.', '..'));
            foreach($files as $file){
                unlink(MIGRATE_PATH.$file);
            }
            rmdir(MIGRATE_PATH);
        }else{
            echo "DB Migration Scripts Deletion Disabled".PHP_EOL;
        }
    }



    protected function check_for_missing($migrate, $files){
        foreach($migrate as $script){
            if(!in_array($script, $files))
            {
                $this->delete_migrate_path();
                echo"\e[31mDB Migration Failed: Misssing File \033[33;5m".$script.PHP_EOL;
                throw NEW \Exception("DB Migration Failed: Misssing File ".$script.PHP_EOL); 
            }
        }
    }

    public function run(){
        $version = $this->version();
        $version = str_replace("v", "", $version);
        $version = explode(".", $version);
        $files = array_diff(scandir(MIGRATE_PATH), array('.', '..', '.ftp-deploy-sync-state.json'));
        natcasesort($files);
        $migrate = $this->db->queryFirstColumn("select file_name from migrate");
        $this->check_for_missing($migrate, $files);
        $version_no = $version[0];
        @$version_step = $version[1];
        foreach($files as $file){
            $file_name = str_replace(".sql", "", $file);
            $info = explode("_", $file_name);
            if(!preg_match('#[^0-9]#',$info[0]) && !preg_match('#[^0-9]#',$info[1])){
                

                    if(in_array($file, $migrate)){
                        echo "\e[34m".$file.PHP_EOL;
                    }else{
                        if( ( (int) $info[0] < (int) $version_no) or ($version_no == (int) $info[0] && (int) $info[1] < $version_step)){
                            $this->delete_migrate_path();
                            echo"\e[31mDB Migration Failed: Invalid Versioning \033[33;5m".$file.PHP_EOL;
                            throw NEW \Exception("DB Migration Failed: Invalid Versioning ".$file.PHP_EOL); 
                        }
                        $this->run_file($file);
                        $this->db->insert('migrate', [
                            'file_name' => $file,
                            'version' => "v".$info[0].".".$info[1]
                        ]);                          
                    }
                    $version = $info[0];
            }else{
                $this->delete_migrate_path();
                echo "\e[31mMigration Failed: Invalid File name: \033[33;5m".$file.PHP_EOL;
                throw NEW \Exception("DB Migration Failed: Invalid File name ".$file.PHP_EOL);
            }
        }
        echo "\e[37mVersion: v".$info[0].".".$info[1].PHP_EOL;

        $this->delete_migrate_path();
        $this->logger->info("DB Migrate End: ".date('Y-m-d H:i:s'));
        echo "__________________________________________________________________".PHP_EOL;
    }
}