<?php
class Dictionary{

    private static $dictionary = array(
        'stars' => array(
            'звезды',
            'звезда',
            'звёзд',
        ),
    );

    public static function getNumSector($transform = 0){
        try{

            if(empty($transform)) throw new Error();

            // родительный
            if(($transform > 4) && ($transform < 21)) return 2;

            $last = $transform%10;

            // винительный
            if(($last == 0) || ($last > 4)) return 2;

            // родительный
            if($last > 1) return 0;

            throw new Error();
        }catch (Error $e){
            // именительный
            return 1;
        }
    }

    public static function get($pointer, $transform = 0){
        try{
            if(empty($pointer)) throw new Error();

            $pointerArr = explode('.', $pointer);

            $arrPointer = self::$dictionary;

            foreach($pointerArr as $item){
                $arrPointer = $arrPointer[$item];
            }

            if($transform){
                return $arrPointer[self::getNumSector($transform)];
            }else{
                return $arrPointer;
            }
        }catch (Error $e){
            return null;
        }
    }
}

?>