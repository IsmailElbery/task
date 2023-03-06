<?php

// if you want to test this code plese un comment the bellow code

/* $obj = new ItemReader('test.txt', 'txt');
$result = $obj->readItems();
if($result){
    print_r($result);
    die();
} */
class ItemReader
{
    private string $filename;
    private string $format;
    public function __construct(string $filename, string $format)
    {
        $this->filename = $filename;
        $this->format = $format;
    }

    public function readItems(): array
    {
        $items = [];
        switch ($this->format) {
            case 'xml':
                $content = file_get_contents($this->filename);
                $data = new xmlRead($content);
                foreach ($data->getElements() as $element) {
                    $item = new Item();
                    $item->id = $element->_id;
                    $item->name = $element->item_name;
                    $items[] = $item;
                }
                break;
            case 'json':
                $content = file_get_contents($this->filename);
                $data = json_decode($content);
                foreach ($data as $element) {
                    $item = new Item();
                    $item->id = $element->id;
                    $item->name = $element->elementName;
                    $items[] = $item;
                }
                break;
            default:
                // some edits happend here
                $ex = new InvalidFormatException();
                $ex->errorMessage();
        }
        return $items;
    }
}
// Define Item Class
class Item
{
    public string $id;
    public string $name;
}
// Define XmlRead Class
class XmlRead
{
    public $content;
    public function __construct($content)
    {
        $this->content = $content;
    }
    public function getElements()
    {
        // convert XML string into an object
        $new = simplexml_load_string($this->content);
        return $new;
    }
}
// handel InvalidFormatException with an error message
class InvalidFormatException extends Exception
{
    public function errorMessage()
    {
        //error message
        echo 'Error on line '.$this->getLine().'</b> with message "File must be a XML File or JSON File Only"';
    }
}
?>
