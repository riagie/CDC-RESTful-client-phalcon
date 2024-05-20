<?php

namespace App\Library;

class Utils
{
    public static function supportedContentTypes()
    {
        $supportedContentTypes = [
            'text/plain', 'application/xml', 'application/json'
        ];

        return $supportedContentTypes;
    }

    public static function setContent($contentType, $data)
    {
        if (in_array($contentType, self::supportedContentTypes())) {
            switch ($contentType) {
                case 'application/xml':
                    return \Libraries\Utils::Xml($data);

                case 'application/json':
                    return \Libraries\Utils::Json('encode', $data);
            }
        }

        $data = $data['DATA'] ?? $data;
        
        if (is_array($data)) {
            return implode('|', array_map(function ($value) {
                return $value === "" ? "0" : $value;
            }, $data));
        }

        return $data;
    }
}
