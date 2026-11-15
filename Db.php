<?php
// Simule une base de données avec des fichiers JSON (dossier data/)
class Db {
    public static function read($name) {
        $file = __DIR__ . "/data/" . $name . ".json";
        return json_decode(file_get_contents($file), true);
    }

    public static function write($name, $data) {
        $file = __DIR__ . "/data/" . $name . ".json";
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    // Prochain identifiant libre pour une liste
    public static function nextId($list, $key) {
        $max = 0;
        foreach ($list as $item) {
            if ($item[$key] > $max) {
                $max = $item[$key];
            }
        }
        return $max + 1;
    }

    public static function find($name, $key, $value) {
        foreach (self::read($name) as $item) {
            if ($item[$key] == $value) {
                return $item;
            }
        }
        return null;
    }
}
