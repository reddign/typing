<?php
require_once '../libs/sql.php';

enum LowercasePolicy: string {
    /** All letters are lowercase */
    case All = 'all';
    /** No policy is applied, words are not changed */
    case None = 'none';
    /** Only the first letter of the first word is capitalized */
    case FirstWord = 'firstword';
    /** Only the first letter of every word is capitalized */
    case FirstLetter = 'firstletter';
}

enum SelectionType: string {
    /** Words are chosen from a database */
    case Database = 'database';
    /** Words are chosen from a text file */
    case File = 'file';
}

class Level {
    public static array $levels = array();
    public string $source_name;
    public string $name;
    public LowercasePolicy $policy;
    public int $words;
    public int $wordlength;
    public SelectionType $type;
    public string $category;

    private function __construct(string $source_name, string $name, LowercasePolicy $policy, int $words, int $wordlength, SelectionType $type, string $category) {
        $this->source_name = $source_name;
        $this->name = $name;
        $this->policy = $policy;
        $this->words = $words;
        $this->wordlength = $wordlength;
        $this->type = $type;
        $this->category = $category;
    }

    /**
     * Creates a typing test according to the level's specifications
     * @return string the text to be typed in the test
     */
    public function get_test(): string {
        global $connection;

        $text = [];
        switch ($this->type) {
            case SelectionType::Database:
                // chose words randomly from a database
                $result = $connection->query("SELECT DISTINCT word FROM "
                    . $connection->escape_string($this->source_name)
                    . ($this->wordlength > 0? " WHERE char_length(word) <= $this->wordlength" : "")
                    . " ORDER BY rand()"
                    . ($this->words > 0? " LIMIT $this->words" : "")
                );

                if (is_array($result)) {
                    foreach ($result as $word) {
                        array_push($text, $word['word']);
                    }
                } else {
                    http_response_code(400);
                    die("Level has an invalid DB table name (\"$this->source_name\")");
                }
                break;
            
            case SelectionType::File:
                // choose a random line from the file
                $file = file("wordlists/$this->source_name.txt", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
                $line = $file[rand(0, sizeof($file) - 1)];
                if ($this->words > 0 && strlen($line) > $this->words) {
                    $text = array_slice(preg_split('/\s+/', $line, $this->words + 1), 0, $this->words);
                } else {
                    $text = preg_split('/\s+/', $line);
                }

                // remove words exceeding the wordlength
                if ($this->wordlength > 0) {
                    $text = array_filter($text, fn ($word) => strlen($word) <= $this->wordlength);
                }
                break;
        }

        // apply lowercase policy
        foreach ($text as $index => &$word) {
            switch ($this->policy) {
                case LowercasePolicy::All:
                    $word = strtolower($word);
                    break;
                case LowercasePolicy::FirstLetter:
                    $word = ucfirst(strtolower($word));
                    break;
                case LowercasePolicy::FirstWord:
                    if ($index == 0) {
                        $word = ucfirst(strtolower($word));
                    } else {
                        $word = strtolower($word);
                    }
                    break;
            }
        }
        return implode(' ', $text);
    }

    /**
     * Get a value or if it doesn't exist, return the default value or throw an error
     */
    private static function get(array $array, string $key, $def = null): mixed {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
        if ($def != null) {
            return $def;
        }
        die("get(): $key is not set and no default was provided");
    }


    /**
     * Attempt to load cached level data. If the cache is dirty or doesn't exist,
     * load_levels() is called and the cache is updated.
     */
    public static function load_cached_levels(): void {
        static $fname = "levelcache.dat";

        $contents = file($fname, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (filemtime($fname) < filemtime("levels.ini") || sizeof($contents) != 1) {
            // level data was changed -> rebuild cache
            Level::load_levels();
            file_put_contents($fname, serialize(Level::$levels));
        } else {
            // load cache from file
            Level::$levels = unserialize($contents[0]);
        }
    }

    /**
     * Load level data from the .ini file and save it to $levels
     */
    private static function load_levels(): void {
        // load levels
        $ini = parse_ini_file('levels.ini', true);
        if (!$ini) {
            echo "Couldn't load levels.ini";
            return;
        }
        foreach ($ini as $key => $value) {
            if (is_array($value)) {
                // key must be a section name if value is an array
                $section = $ini[$key];
                $level = new Level(
                    $key, 
                    Level::get($section, 'name'),
                    LowercasePolicy::tryFrom(Level::get($section, 'lowercase', 'none')),
                    intval(Level::get($section, 'words', '-1')),
                    intval(Level::get($section, 'wordlength', '-1')),
                    SelectionType::tryFrom(Level::get($section, 'selection')),
                    Level::get($section, 'category', "\0")
                );
                
                // add level to $levels under its category
                $categories = explode('.', $level->category );
                $map = &Level::$levels;
                foreach ($categories as $category) {
                    if ($category == "\0") {
                        continue;
                    }
                    if (!isset($map[$category])) {
                        $map[$category] = [];
                    }
                    $map = &$map[$category];
                }
                array_push($map, $level);
            }
        }
    }

    /**
     * Recursively get a level by the name of its source file/database
     * @param string $source_name the source_name of the level to get
     * @return Level|false The corresponding level, or false if it couldn't be found
     */
    public static function get_level(string $source_name, $array = null): Level | false {
        if ($array == null) {
            // $array can't be assigned to $levels in the param list because get_level is a static function
            $array = Level::$levels;
        }
        foreach ($array as &$level) {
            if (is_array($level)) {
                $has_level = Level::get_level($source_name, $level);
                if ($has_level instanceof Level) {
                    return $has_level;
                }
            } else if ($level instanceof Level && $level->source_name == $source_name) {
                return $level;
            }
        }
        return false;
    }

    /**
     * Echo out all the levels and their categories
     */
    public static function echo(): void {
        if (empty(Level::$levels)) {
            Level::load_cached_levels();
        }

        // put levels in their categories
        Level::print_category_map(Level::$levels, '', 0);
    }

    /** Recursively print a map */
    private static function print_category_map(array &$map, string $category, int $layer): void {
        if ($layer > 0) {
            if ($layer > 6) {
                $layer = 6;
            }
            echo "<h$layer>$category</h$layer>";
        }
        echo "<ul>";
        // add tests in the category, then add subcategories
        foreach ($map as $key => $value) {
            if ($value instanceof Level) {
                echo "<li><a href=\"../test/?level=$value->source_name\">$value->name</a></li>";
            }
        }
        foreach ($map as $key => $value) {
            if (is_array($value)) {
                Level::print_category_map($value, $key, $layer + 1);
            }
        }
        echo "</ul>";
    }
}

?>