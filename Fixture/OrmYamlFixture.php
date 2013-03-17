<?php

namespace Khepin\YamlFixturesBundle\Fixture;

use Doctrine\Common\Util\Inflector;

class OrmYamlFixture extends AbstractFixture
{
    public function createObject($class, $data, $metadata, $options = array())
    {
        $mapping = array_keys($metadata->fieldMappings);
        $associations = array_keys($metadata->associationMappings);

        $object = $this->instantiate($class, $data, $metadata, $mapping, $associations);

        unset($data['()']);

        foreach ($data as $field => $value) {
            // Add the fields defined in the fixtures file
            $value = $this->determineValue(
                $value, $field, $metadata, $mapping, $associations
            );

            $method = Inflector::camelize('set_' . $field);
            $object->$method($value);
        }

        $this->runServiceCalls($object);

        return $object;
    }

    /**
     * @param  string $class
     * @param  array  $data
     * @param  array  $metadata
     * @param  array  $mapping
     * @param  array  $associations
     * @return mixed
     */
    private function instantiate($class, $data, $metadata, $mapping, $associations)
    {
        if (isset($data['()'])) {
            $reflection = new \ReflectionClass($class);

            $arguments = array();
            foreach ($data['()'] as $field => $value) {
                $arguments[] = $this->determineValue(
                    $value, $field, $metadata, $mapping, $associations
                );
            }

            return $reflection->newInstanceArgs($arguments);
        }

        return new $class;
    }

    /**
     * @param  string $value
     * @param  string $field
     * @param  array  $metadata
     * @param  array  $mapping
     * @param  array  $associations
     * @return mixed
     */
    private function determineValue($value, $field, $metadata, $mapping, $associations)
    {
        if (in_array($field, $mapping)) {
            // Dates need to be converted to DateTime objects
            $type = $metadata->fieldMappings[$field]['type'];
            if ($type == 'datetime' OR $type == 'date') {
                $value = new \DateTime($value);
            }

            return $value;
        }

        if (in_array($field, $associations)) { // This field is an association
            if (is_array($value)) { // The field is an array of associations
                $referenceArray = array();
                foreach ($value as $referenceObject) {
                    $referenceArray[] = $this->loader->getReference($referenceObject);
                }

                return $referenceArray;
            }

            return $this->loader->getReference($value);
        }

        return $value;
    }
}
