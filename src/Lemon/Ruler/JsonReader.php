<?php
namespace Lemon\Ruler;

/**
 * Creates Ruler rules from json
 */
class JsonReader extends ArrayReader
{
    public function build($json)
    {
        if (is_file($json)) {
            $json = file_get_contents($json);
        }

        $rule = json_decode($json, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid JSON[%s]: %s", 
                    json_last_error(), 
                    json_last_error_msg()
                )
            );
        }

        return parent::build(
            $rule
        );
    }
}