<?php
namespace Taskforce\DB;

use SplFileObject;
use Taskforce\Exceptions\SourceFileException;

class CsvConverter
{
    private $name = '';
    private $path = '';
    private $columns = [];
    private $rows = [];
    
    /**
     * Loads a file to converter
     *
     * @param  string $filepath relative path to the file
     * @return void
     */
    public function __construct(string $filepath)
    {
        if (!file_exists($filepath)) {
            throw new SourceFileException('The file doesn\'t exist');
        }
        if (!fopen($filepath, 'r')) {
            throw new SourceFileException('Can\'t read the file');
        }
        $file = new SplFileObject($filepath);
        $this->name = $file->getBasename('.csv');
        $this->path = $file->getPath();
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
        foreach ($file as $row) {
            $this->rows[] = $row;
        }
        $this->columns = array_shift($this->rows);
        if (empty($this->columns) || empty($this->rows)) {
            throw new SourceFileException('The file must contain at least two rows');
        }
    }
    
    /**
     * Generates values for the column
     *
     * @param  string $column column name
     * @return string|int random or template value
     */
    private function generateValue(string $column) {
        $random_values = [
            'user_id' => 20,
            'customer_id' => 20,
            'executor_id' => 20,
            'sender_id' => 20,
            'recipient_id' => 20,
            'category_id' => 8,
            'task_id' => 10,
            'city_id' => 1108,
            'had_problems' => 1,
            'views' => 10000,
            'offer' => 90000,
            'is_declined' => 1,
            'accepted_reply' => 20
        ];
        $templates = [
            'categories' => 'translation, clean',
            'notifications' => 'message, action',
            'status' => ['new', 'canceled', 'active', 'completed', 'failed'][rand(0,4)],
            'last_activity_time' => date('Y-m-d H:i:s', time())
        ];
        if (array_key_exists($column,$random_values)) {
            $max = $random_values[$column];
            $min = $max===1?0:1;
            return rand($min, $max);
        }
        if (array_key_exists($column,$templates)) {
            return $templates[$column];
        }
        return 'textPlaceholder';
    }
    
    /**
     * Converts loaded .csv file to a SQL INSERT string
     *
     * @return string INSERT string
     */
    private function convertToSql(): string
    {
        $columns = [];
        foreach ($this->columns as $column) {
            $columns[] = '`'.$column.'`';
        }
        $rows = $this->rows;
        $values = [];
        foreach ($rows as $row) {
            for ($i=0; $i < count($columns); $i++) {
                if (!isset($row[$i])) {
                    $row[$i] = $this->generateValue($this->columns[$i]);
                }
                if (!is_numeric($row[$i])) {
                    $row[$i] = var_export($row[$i], true);
                }
            }
            $values[] = '('.implode(', ', $row).')';
        };
        return 'INSERT INTO `'.$this->name.'` ('.implode(', ', $columns).') VALUES '.implode(', ', $values).';';
    }
    
    /**
     * Saves converted into INSERT string .csv file to .sql with the same name to the same folder
     *
     * @return void
     */
    public function saveToSql() {
        $file = new SplFileObject($this->path.'/'.$this->name.'.sql', "w");
        $file->fwrite($this->convertToSql());
    }
}
