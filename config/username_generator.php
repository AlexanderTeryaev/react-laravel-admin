<?php

use App\User;
use TaylorNetwork\UsernameGenerator\Drivers\EmailDriver;
use TaylorNetwork\UsernameGenerator\Drivers\NameDriver;

return [

    /*
     * Should the username generated be unique?
     */
    'unique' => true,

    /*
     * The minimum length of the username.
     *
     * Set to 0 to not enforce
     */
    'min_length' => 4,

    /*
     * Want to throw a UsernameTooShort exception when too short?
     */
    'throw_exception_on_too_short' => false,

    /*
     * Convert the case of the generated username
     *
     * One of 'lower', 'upper', or 'mixed'
     */
    'case' => 'lower',

    /*
     * Convert spaces in username to a separator
     */
    'separator' => '-',

    /*
     * Model to check if the username is unique to.
     *
     * This is only used if unique is true
     */
    'model' => User::class,

    /*
     * Database field to check and store username
     */
    'column' => 'username',

    /*
     * Allowed characters from the original unconverted text
     */
    'allowed_characters' => 'a-zA-Z ',

    /*
     * Loaded drivers for converting to a username
     */
    'drivers' => [
        'name'  => NameDriver::class,
        'email' => EmailDriver::class,
    ],

    /*
     * Used if you pass null to the generate function, will generate a random username
     */
    'dictionary' => [
        'adjectives' => [
            'black',
            'white',
            'red',
            'blue',
            'yellow',
            'orange',
            'green',
            'violet',
            'beige',
            'pink',
            'purple',
            'brown',
            'grey',
            'silver',
            'azure',
            'gray',
            'rose',
            'turquoise',
            'amiable',
            'diligent',
            'generous',
            'rational',
            'reliable',
            'sensible',
            'witty',
            'honest',
            'lucky',
            'loyal',
            'rich',
            'caring',
            'brave',
            'angelic',
            'fit',
            'fancy',
            'little',
            'small',
            'tiny',
            'angry',
            'nice',
            'proud'
        ],

        'nouns' => [
            'aardvark',
            'antelope',
            'bass',
            'bear',
            'boar',
            'buffalo',
            'calf',
            'carp',
            'catfish',
            'cavy',
            'cheetah',
            'chicken',
            'chub',
            'clam',
            'crab',
            'crayfish',
            'crow',
            'deer',
            'dogfish',
            'dolphin',
            'dove',
            'duck',
            'elephant',
            'flamingo',
            'flea',
            'frog',
            'fruitbat',
            'giraffe',
            'gnat',
            'goat',
            'gorilla',
            'gull',
            'haddock',
            'hamster',
            'hare',
            'hawk',
            'herring',
            'honeybee',
            'housefly',
            'kiwi',
            'ladybird',
            'lark',
            'leopard',
            'lion',
            'lobster',
            'lynx',
            'mink',
            'mole',
            'mongoose',
            'moth',
            'newt',
            'octopus',
            'opossum',
            'oryx',
            'ostrich',
            'parakeet',
            'penguin',
            'pheasant',
            'pike',
            'piranha',
            'pitviper',
            'platypus',
            'polecat',
            'pony',
            'porpoise',
            'puma',
            'pussycat',
            'raccoon',
            'reindeer',
            'rhea',
            'scorpion',
            'seahorse',
            'seal',
            'sealion',
            'seasnake',
            'seawasp',
            'skimmer',
            'skua',
            'slowworm',
            'slug',
            'sole',
            'sparrow',
            'squirrel',
            'starfish',
            'stingray',
            'swan',
            'termite',
            'toad',
            'tortoise',
            'tuatara',
            'tuna',
            'vampire',
            'vole',
            'vulture',
            'wallaby',
            'wasp',
            'wolf',
            'worm',
            'wren',
            'apple',
            'apricot',
            'avocado',
            'banana',
            'bilberry',
            'blackberry',
            'blackcurrant',
            'blueberry',
            'boysenberry',
            'breadfruit',
            'cantaloupe',
            'cherimoya',
            'cherry',
            'clementine',
            'cloudberry',
            'coconut',
            'cranberry',
            'cucumber',
            'currant',
            'damson',
            'date',
            'dragonfruit',
            'durian',
            'eggplant',
            'elderberry',
            'feijoa',
            'fig',
            'gooseberry',
            'grape',
            'grapefruit',
            'guava',
            'honeydew',
            'huckleberry',
            'jackfruit',
            'jambul',
            'jujube',
            'kumquat',
            'lemon',
            'lime',
            'loquat',
            'lychee',
            'mandarine',
            'mango',
            'mulberry',
            'nectarine',
            'nut',
            'olive',
            'orange',
            'pamelo',
            'papaya',
            'passionfruit',
            'peach',
            'pear',
            'persimmon',
            'physalis',
            'pineapple',
            'plum',
            'pomegranate',
            'pomelo',
            'quince',
            'raisin',
            'rambutan',
            'raspberry',
            'redcurrant',
            'satsuma',
            'strawberry',
            'tamarillo',
            'tangerine',
            'tomato',
            'watermelon'
        ],
    ],

];
