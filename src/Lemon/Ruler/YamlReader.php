<?php
namespace Lemon\Ruler;

/**
 * Creates Ruler rules from Yaml
 */
class YamlReader extends ArrayReader
{
    public function build($yaml)
    {
        if (!extension_loaded('yaml')) {
            throw new \RuntimeException(
                "Yaml extension must be loaded to use the yaml reader"
            );
        }
        
        if (is_file($yaml)) {
            $yaml = file_get_contents($yaml);
        }

        if (!($rule = yaml_parse($yaml))) {
            throw new \InvalidArgumentException("Invalid Yaml");
        }

        return parent::build(
            $rule
        );
    }
}
