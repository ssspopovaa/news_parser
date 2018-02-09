<?php
/**
 * Класс Router выбирает нужные методы и классы Parser
 * в зависимости от нажатых кнопок формы
 */
class Router {
    
    public function run() {
    $table = array();
        require 'Parser.php';
        if (!empty($_POST['start'])) {
            
            echo '<table border = 2px><tr>
                    <td>Номер новости</td>
                    <td>Время парсинга</td>
                    <td>Время новости</td>
                    <td>Заголовок новости ссылка</td>
                    <td>Текст новости</td>
                    <td>Метка (Фото, Фидео)</td>
             </tr>';
            $parser = new Parser;
            $table = $parser->parser();
            
        }
        if (!empty($_POST['clear'])) {
            $table=array();
            Parser::clear();
        }
        if (!empty($_POST['saveFile'])) {
            AddToFile::AddToFile($table);
        }
        
        if (!empty($_POST['saveDb'])) {
            AddToDb::AddToDb($table);
        }
    }

}
