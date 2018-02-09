<?php
/**
 * Класс Parser делает парсинг заданной страницы в зависимости от параметров
 * формы или очищает страницу
 */

class Parser {

    /**
     * Метод parser получает данные с заданной страницы и выводит на экран
     * заданные данные
     */
    public function __construct() {
        
    }
    public static function parser() {

        require 'phpQuery.php';
        

        $url = 'http://www.pravda.com.ua/rus/news/';

        $file = file_get_contents($url);

        $doc = phpQuery::newDocument($file, $contentType = null);
        
        /**
         * Если пользователь не указал количество новостей,
         * указываем 1000
         */
        if(empty($_POST['news_count'])){
        $newsCount = 1000;}
            else{
            $newsCount = $_POST['news_count'];}
        $i = 1;
        
        $table = array();
        $date = date('Y-m-d,H:i:s');  
        /**
         * В цикле выводим каждую новость, которая находится под классом
         * article
         */
        foreach ($doc->find('.news_all .article') as $article) {
            $article = pq($article);
            //$article->find('.cat')->wrap('<div class = "category">')->after('Дата: '. date("Y-m-d H:i:s"));
            //$img = $article->find('.img-cont img')->attr('src');
            //$text = $article->find('.article__title')->html();
            
                // В переменную $title помещаем заголовок новости
            $href = $article->find('.article__title')->attr('href');
            $title = $article->find('.article__title')->text();
            $title = '<a href ='. $href.'>'.$title.'</a>';
                        
                //В переменную $article__time помещаем время новости
            $article__time = $article->find('.article__time')->text();
            
            ////В переменную $text помещаем саму новость
            $text = $article->find('.article__subtitle')->text();
            $mark = $article->find('.article__title em')->text();
            //echo "<img src = '$img'>";
            //echo '<hr>';
            
            /**
             * Выводим на экран заданное количество новостей,
             * равное итерациям цикла foreach
             */
            if ($newsCount>= $i){
              
            //Вывод данных в таблицу
            echo 
                '<tr>
                    <td>'.$i.'</td>
                    <td>'.$date.'</td>
                    <td>'.$article__time.'</td>
                    <td>'.$title.'</td>
                    <td>'.$text.'</td>
                    <td>'.$mark.'</td>
             </tr> ';
           
            /**
             *  Создание многомерного массива, для возможного сохранения
             * данных в файл или базу данных
             */
            
           $table[$i]['N']=$i;
           $table[$i]['date']=$date;
           $table[$i]['article__time']=$article__time;
           $table[$i]['title']=$title;
           $table[$i]['text']=$text;
           $table[$i]['mark']=$mark;
           
           $i++;
           
           } else {
                break;    
            }
        }
        $_SESSION['table']= $table;
        echo '</table>';
        echo '<form method = "POST" action = "index.php">'
        . '<input type= "submit" name = "saveFile" value = "Сохранить файл">'.'<br>'.
          '<input type= "submit" name = "saveDb" value = "Сохранить в базу данных">'
           . '</form>'.'<br>';
      return $table;
    }
    
    public static function clear(){
        echo '';
    }

}
class AddToFile {

    public function __construct() {
        
    }
  public static function AddToFile() {
        $table = $_SESSION['table'];

        $fp = fopen('file.csv', 'w');

        foreach ($table as $fields) {
        fputcsv($fp, $fields);
}

        fclose($fp);
        echo '<a href="file.csv" download="file.csv">Скачать</a>';
   }

}

class AddToDb {

    public function __construct() {
    }
    public static function getConnection()
    {
        $paramsPath = '/config/db_params.php';
        $params = include($paramsPath);
        

        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $db = new PDO($dsn, $params['user'], $params['password']);
        $db->exec("set names utf8");
        
        return $db;
    }

 public static function AddToDb() {
     
        $db = AddToDb::getConnection();

        $table = $_SESSION['table'];
        
        foreach ($table as $item){
        $sql = 'insert into parser (id_news, date_pars, News_time, header, text, mark)'
                . 'VALUES ('.$item['N'].','. $item['date'].','. $item['article__time'].','
                . $item['title'].','.$item['text'].','. $item['mark'].')';
        return $db->query($sql);  
        }
    }
}