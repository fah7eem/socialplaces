<?php 
namespace Monolog\Handler;
use Monolog\Logger;

class DBLogger extends AbstractProcessingHandler{

    private $db;
    private $table;
    public function __construct($db_table, $level = Logger::ERROR, bool $bubble = true){
        $this->table = $db_table;
        parent::__construct($level, $bubble);
        $this->db = new \MeekroDB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT, CHARSET);
    }

    public function write(array $record): void
    {
        @$path = parse_url($_SERVER['REQUEST_URI'])['path'];
        if($path != "/" and  $record["message"] !== "Token not found"){
            try{
                    $this->db->insert($this->table, [
                        'channel' => $record["channel"],
                        'level' => $record["level_name"],
                        'message' => $record["message"],
                        'path' => $path
                    ]);
            }catch(\MeekroDBException $e){
                echo $record["message"].PHP_EOL;
                echo $e->getMessage().PHP_EOL;
            }
        }
    }
}