<?php

namespace App\Plugin;

class Models
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function formatModels()
    {
        $format  = '<?php ';
        $format .= 'namespace App\Model; ';
        $format .= 'use Phalcon\Mvc\Model; ';
        $format .= 'class #CLASS# extends Model ';
        $format .= '{ ';
        $format .= '    private $db; ';
        $format .= '    public function initialize() ';
        $format .= '    { ';
        $format .= '        $this->db = $this->getDI()->get("db"); ';
        $format .= '        $this->setSource("#TABLE#"); ';
        $format .= '    } ';
        $format .= "    \n\n // set-your-code \n\n";
        $format .= '    public function columnMap() ';
        $format .= '    {';
        $format .= '        return #FIELD# ';
        $format .= '    }';
        $format .= '}';

        return htmlentities(urldecode($format));
    }

    public function createModels()
    {
        $sql   = "SHOW TABLES";
        $query = $this->connection->query($sql);
        $query->setFetchMode(\Phalcon\Db\Enum::FETCH_ASSOC);
        foreach ($query->fetchAll() as $value) {
            $value  = array_values($value)[0];
            $format = explode("_", $value);
            $format = (count($format) > 1) ? array_slice($format, 1) : $format;
            $format = array_reduce($format, function($data, $item) {
                $item = trim($item);
                $item = strtolower($item);
                $item = ucfirst($item);
                
                return $data . $item;
            }, '');

            $directory = APP_PATH . "/models/" . $format . ".php";
            if (!file_exists($directory)) {
                $format = array_reduce(["table" => $value], function($data, $item) use ($format) {
                    $sql   = "DESCRIBE " . $item;
                    $query = $this->connection->query($sql);
                    $query->setFetchMode(\Phalcon\Db\Enum::FETCH_ASSOC);

                    $column  = "";
                    foreach ($query->fetchAll() as $value) {
                        $key = explode("_", $value["Field"]);
                        array_splice($key, 0, 2);
                        $key = strtolower(implode("_", $key));
                        
                        $column .= "\n";
                        $column .= "'" . $value["Field"] . "' ";
                        $column .= "=> ";
                        $column .= "'" . $key . "'";
                        $column .= ",";
                    }
                    $column = "[" . $column . "\n];";

                    $items = str_replace("#CLASS#", $format, $this->formatModels());
                    $items = str_replace("#TABLE#", $item, $items);
                    $items = str_replace("#FIELD#", $column, $items);

                    return $data . $items;
                }, '');

                $format = file_put_contents($directory, htmlspecialchars_decode($format));
                $format = '"' . BASE_PATH . '/vendor/bin/php-cs-fixer" fix "' . $directory . '" --config="' . (__DIR__) . '/PhpCsFixer.php"';
                $format = shell_exec($format);
            }

            return true;
        }

        return;
    }
}
