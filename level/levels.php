<?php

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
    public SelectionType $type;
    public string $category;

    private function __construct(string $source_name, string $name, LowercasePolicy $policy, int $words, SelectionType $type, string $category) {
        $this->source_name = $source_name;
        $this->name = $name;
        $this->policy = $policy;
        $this->words = $words;
        $this->type = $type;
        $this->category = $category;
    }

    /**
     * Creates a typing test according to the level's specifications
     * @return string the text to be typed in the test
     */
    public function get_test(): string {
        return '';
    }

    /**
     * Get a value or if it doesn't exist, return the default value or throw an error
     */
    private static function get($val, $def = null): mixed {
        if (isset($val)) {
            return $val;
        }
        if ($def != null) {
            return $def;
        }
        die("get(): \$val is not set and no default was provided");
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
                    Level::get($section['name']),
                    LowercasePolicy::tryFrom(Level::get($section['lowercase'], 'none')),
                    intval(Level::get($section['words'], 1)),
                    SelectionType::tryFrom(Level::get($section['selection'])),
                    Level::get($section['category'], '')
                );
                
                // add level to $levels under its category
                $categories = explode('.', $level->category );
                $map = &Level::$levels;
                foreach ($categories as $category) {
                    if ($category == '') {
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
     * Get a level by the name of its source file/database
     * @param string $source_name the source_name of the level to get
     * @return Level|false The corresponding level, or false if it couldn't be found
     */
    public static function get_level(string $source_name): Level | false {
        foreach (Level::$levels as $level) {
            if ($level->source_name == $source_name) {
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
            Level::load_levels();
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