<?php
namespace core\common;
use core\common\regex;

class grammar 
{

    const irregular_nouns = array
    (
        "sheep"=>"sheep",
        "fish"=>"fish",
        "deer"=>"deer",
        "moose"=>"moose",
        "phenomenon"=>"phenomena",
        "swine"=>"swine",
        "buffalo"=>"buffalo",
        "shrimp"=>"shrimp",
        "trout"=>"trout",
        "aircraft"=>"aircraft",
        "watercraft"=>"watercraft",
        "hovercraft"=>"hovercraft",
        "spacecraft"=>"spacecraft",
        "foot"=>"feet",
        "tooth"=>"teeth",
        "goose"=>"geese",
        "man"=>"men",
        "woman"=>"women",
        "potato"=>"potatoes",
        "tomato"=>"tomatoes",
        "hero"=>"heroes",
        "torpedo"=>"torpedoes",
        "veto","vetoes",

    );
    
    /*
        1. Most singular nouns need an 's' at the end to become plural.
        2. Singular nouns ending in 's', 'ss', 'sh', 'ch', 'x', or 'z' need an 'es' at the end to become plural.
        Note that some singular nouns ending in 's' or 'z' require that you double the 's' or 'z' before adding an 'es'. For example, a really bad day might involve you having not one pop quiz, but two pop quizzes.
        Irregular Plural Nouns
        3. Some nouns are the same in both their singular and plural forms.
        4. Some nouns ending in 'f' require that you change the 'f' to a 'v' and then add an 'es' at the end to make them plural.
        To make a plural of a word ending in -f, change the f to a v and add es.
        5. Nouns that end in 'y' often require that you change the 'y' to an 'i', and then add an 'es' at the end to make them plural.
        6. To make a word ending in -us plural, change -us to -i.
        7. Nouns with an -is ending can be made plural by changing -is to -es. 
        8. These Greek words change their -on ending to -a. (phenomenon,criterion)
        9. Words ending in -um shed their -um and replace it with -a to form a plural.
        10. Nouns ending in -ix are changed to -ices in formal settings, but sometimes -xes is perfectly acceptable. 
    */

    public function get_plural($pword)
    {

    }

    public function get_singular($pword)
    {

    }

}