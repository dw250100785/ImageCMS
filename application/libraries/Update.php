<?php

/*
 * ДОРОБИТИ розархівування файлів, апі настройок, тестування, продумати права на папки
 */

class Update {

    private $arr_files;
    public $path_parse; // шлях до сканування папок
    public $update_directory = 'update'; // назва папки з обновленням відносно корня сайту
    private $distinct = array('.', '..', '.git'); // папки, які не враховувати при обновлені
    public $old_reliz = 'old_relith'; // назва архіву і папки з скачаним старим текущим релізом в оригіналі 
    public $update_file = 'update_file'; // назва архіву і папки з скачаним обновленням
    public $marge_file = 'marge_file'; // назва папки з обєднаними файлами
    public $path_update = ''; // шлях до архіву з обновленням
    public $path_old_relith = ''; // шлях до архіву старого релізу

    public function __construct() {
        
    }
    
     /*  Оприділення шляхів відносно настройок
     */

    public function paths() {

        // шлях до корня сайту /var/www
        $this->dir_curr = realpath('') . DIRECTORY_SEPARATOR;
        // шлях до папки з обновленням з "/" вкінці
        $this->dir_old_upd = realpath('') . DIRECTORY_SEPARATOR . $this->update_directory . DIRECTORY_SEPARATOR . $this->old_reliz . DIRECTORY_SEPARATOR;
        // шлях до папки з старим редізом з "/" вкінці
        $this->dir_upd = realpath('') . DIRECTORY_SEPARATOR . $this->update_directory . DIRECTORY_SEPARATOR . $this->update_file . DIRECTORY_SEPARATOR;
        // шлях до папки з обэднаними файлами з "/" вкінці
        $this->dir_marge = realpath('') . DIRECTORY_SEPARATOR . $this->update_directory . DIRECTORY_SEPARATOR . $this->marge_file . DIRECTORY_SEPARATOR;

        // шлях до файлу з масивом про дані текущих файлів
        $this->file_mass_curr = $this->update_directory . DIRECTORY_SEPARATOR . 'current_mas.txt';
        // шлях до файлу з масивом про дані старого релізу файлів
        $this->file_mass_old = $this->update_directory . DIRECTORY_SEPARATOR . $this->old_reliz . '_mas.txt';
        // шлях до файлу з масивом про дані файлів які різняться
        $this->file_mass_diff = $this->update_directory . DIRECTORY_SEPARATOR . 'diff_mas.txt';
        // шлях до файлу з масивом про дані файлів які не обєднюються
        $this->file_dont_marge = $this->update_directory . DIRECTORY_SEPARATOR . 'dont_marge_mas.txt';


        // шлях до файлу з архівом старого релізу
        $this->file_zip_old = $this->update_directory . DIRECTORY_SEPARATOR . $this->old_reliz . '.zip';
        // шлях до файлу з архівом обновлення
        $this->file_zip_upd = $this->update_directory . DIRECTORY_SEPARATOR . $this->update_file . '.zip';
    }

    /* Вказуються папки, як пропускаються в обновленні
     */

    public function set_distinct($array) {

        $this->distinct = array_merge($this->distinct, $array);
        return $this;
    }

    /* Скачує і розархівовує архіви обновлення і архів старої теперішньої версії. 
     * Записує і розпаковує у відповідні файли і папки які вказуються в настройках
     * Доробити розархівування
     */

    public function download_and_unzip() {

        copy($this->path_update, $this->file_zip_upd);
        copy($this->path_old_reliz, $this->file_zip_old);

        //unzip() to $this->update . DIRECTORY_SEPARATOR . $label
    }

    /*  Бере контрольні суми файлів текущих файлів і файлів старої теперішньої версії 
     * Записує іх у відповідні файли з настройок, як серіалізований масив ключ - шлях до файлу, значення - контрольна сума
     * запускати два рази переоприділивши $this->path_parse
     * $this->path_parse = realpath('') текущі.
     * $this->path_parse = rtrim($this->dir_old_upd, '\')
     */

    public function parse_md5($directory = null) {

        $dir = null === $directory ? $this->path_parse : $directory;

        if ($handle = opendir($dir))
            while (FALSE !== ($file = readdir($handle)))
                if (!in_array($file, $this->distinct)) {
                    if (is_file($dir . DIRECTORY_SEPARATOR . $file))
                        $this->arr_files[$dir . DIRECTORY_SEPARATOR . $file] = md5($dir . DIRECTORY_SEPARATOR . $file);
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $file))
                        $this->parse_md5($dir . DIRECTORY_SEPARATOR . $file);
                }

        strstr($this->path_parse, $this->update_directory) ? file_put_contents($this->file_mass_old, serialize($this->arr_files)) : file_put_contents($this->file_mass_curr, serialize($this->arr_files));

        //return $this->arr_files;
    }

    /*  Аналізує які файли текущі відрізняються від старих текущих файлів версії 
     * результат записується у відаповідний файл з настройок, як серіалізований масив значення якого - шлях до файлу від "application"
     */

    public function get_analiz_differents() {

        $arr_current = unserialize(file_get_contents($this->file_mass_curr));
        $arr_old = unserialize(file_get_contents($this->file_mass_old));
        $arr_diff = array();
        foreach ($arr_current as $file => $value) {
            $file_key = $this->dir_old_upd . str_replace($this->dir_curr, '', $file);
            if ($arr_current[$file] != $arr_old[$file_key])
                $arr_diff[] = str_replace($this->dir_curr, '', $file);
        }

        file_put_contents($this->file_mass_diff, serialize($arr_diff));
    }



    /*  Спроба обєднання файлів які різняться 
     * записує файли які вдалося обєднати у відповідну з настройок деректорії, 
     * та записується масив файлів у відповідний з настройок файл, фкі не вдалося обєднати, ключ масиву - шлях до файлу
     */

    public function parse_to_marge() {

        $arr_diff = unserialize(file_get_contents($this->file_mass_diff));
        foreach ($arr_diff as $file) {
            if ($marge = $this->marging($file)) {
                if (!$marge['dont_marge'])
                    file_put_contents($this->dir_marge . $file, implode("\n", $marge['marge_file']));
                else
                    $arr_dont_marge[] = $file;
            }
        }

        file_put_contents($this->file_dont_marge, serialize($arr_dont_marge));
    }

    /*  порядкова система обєднання файлів, які різняться  
     */

    public function marging($file = null) {

        $file_curr = file_get_contents($this->dir_curr . $file);
        $file_old = file_get_contents($this->dir_old_upd . $file);
        if (file_exists($this->dir_upd . $file)) { // якщо файл обновлення існує
            $file_upd = file_get_contents($this->dir_upd . $file);
            
            // видалення пустих рядків у файлах
            $file_curr_arr = $this->delete_baks($file_curr);
            $file_old_arr = $this->delete_baks($file_old);
            $file_upd_arr = $this->delete_baks($file_upd);

            $marge_file = array();

            foreach ($file_curr_arr as $line => $data) {
                if ($file_curr_arr[$line] == $file_old_arr[$line] and $file_curr_arr[$line] == $file_upd_arr[$line])
                    $marge_file[] = $file_curr_arr[$line]; // якщо рядки файлів збігаються
                if ($file_curr_arr[$line] != $file_old_arr[$line] and $file_old_arr[$line] == $file_upd_arr[$line])
                    $marge_file[] = $file_curr_arr[$line]; // якщо рядок обновлення збігається зі старим, а текущий інший
                if ($file_curr_arr[$line] == $file_old_arr[$line] and $file_old_arr[$line] != $file_upd_arr[$line])
                    $marge_file[] = $file_upd_arr[$line]; // якщо рядок старий і теперішній однакові, а обновлення інший
                if ($file_curr_arr[$line] != $file_old_arr[$line] and $file_old_arr[$line] != $file_upd_arr[$line]) {
                    $marge_dont = true; // якщо рядоки різні
                    break;
                }
            }

            return array('dont_marge' => $marge_dont, 'marge_file' => $marge_file);
        }
        else
            return false;
    }

    /*  видалення пустих рядків у файлах ???????????  
     */

    private function delete_baks($file) {

        $file_line_arr = explode("\n", $file);
        foreach ($file_line_arr as $line => $data) {
            if (trim($data) == '')
                unset($file_line_arr[$line]);
        }

        return $file_line_arr;
    }

    /*  Заміна файлів з обновлення
     * 1. Заміняються файли, які не відрізняються від старої текущої версії
     * 2. заміняються файли які вдалося обєднати
     * 3. Створюється файл з приставкою _update в текущій папці даного файлу (користувач сам обєднює такі файли або обєднює такі файли система, не несучи за це відповідальності)
     */

    public function replacement() {

        $arr_curr_file = unserialize(file_get_contents($this->file_mass_curr));
        $diff_arr_file = unserialize(file_get_contents($this->file_mass_diff));
        $dont_arr_marge_file = unserialize(file_get_contents($this->file_dont_marge));
        foreach ($arr_curr_file as $file => $data) {
            if (file_exists($this->dir_upd . $file)) {
                if (!in_array($this->dir_upd . $file, $diff_arr_file)) {
                    unlink($this->dir_curr . $file);
                    copy($this->dir_upd . $file, $this->dir_curr . $file);
                } else {
                    if (!in_array($this->dir_upd . $file, $dont_arr_marge_file)) {
                        unlink($this->dir_curr . $file);
                        copy($this->dir_marge . $file, $this->dir_curr . $file);
                    }
                    else
                        copy($this->dir_upd . $file . '_update', $this->dir_curr . $file);
                }
            }
        }
    }

    public function get_settings() {
        
    }

    public function set_settings() {
        
    }

}