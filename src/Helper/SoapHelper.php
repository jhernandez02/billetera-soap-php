<?php 
namespace App\Helper;

class SoapHelper
{
    public static function toArray($data): array
    {
        $array = [];

        if (is_object($data) && isset($data->item)) {
            $items = is_array($data->item) ? $data->item : [$data->item];

            foreach ($items as $item) {
                if (isset($item->key) && isset($item->value)) {
                    $array[$item->key] = $item->value;
                }
            }
        }

        return $array;
    }

    public static function toSoapMap(array $assoc): array
    {
        $map = [];

        foreach ($assoc as $key => $value) {
            $map[] = ['key' => $key, 'value' => $value];
        }
        
        return $map;
    }
}
