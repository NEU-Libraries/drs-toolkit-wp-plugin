<?php

namespace Ceres\Util;

class StringUtilities {
    

    // from https://gist.github.com/carousel/1aacbea013d230768b3dec1a14ce5751
    public static function camelCaseToSnakeCase($input) {
        $input = str_replace('_', '', $input); //to work with _ in class names
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    // from https://gist.github.com/carousel/1aacbea013d230768b3dec1a14ce5751
    public static function snakeCaseToCamelCase($input) {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }

    public static function languageToSnakeCase($input) {
        $output = strtolower($input);
        $output = preg_replace("/( )\\1+/", "$1", $output); // remove repeating spaces
        $output = preg_replace("#[[:punct:]]#", "", $output); // do this before _ because [[:punct:]] includes _   
        $output = str_replace(' ', '_', $output);
        return $output;
    }


    public static function createNameIdForInstantiation($instantiation, $desc='') { //sub in actual class
        //test if I'm given an instantiated obj, or the class name to instantiate
    
    
        // $rendererClassName = $viewPackages['tabular_wikidata_for_short_metadata']['rendererClassName'];
        // $renderer = new $rendererClassName();
        $rendererUnQualifiedName = get_class($instantiation);
    
        $refClass = new \ReflectionClass($instantiation);
        $shortName = $refClass->getShortName();
    
        $name = self::camelCaseToSnakeCase($shortName) . "_" . self::languageToSnakeCase($desc);
        //echo $name;
        //echo "$shortName \n $name";
    
        //snake to language?
        return ucfirst(str_replace('_', ' ', $name));
    }




}


