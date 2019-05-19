<?php

declare(strict_types=1);

namespace Contao\CoreBundle\EventListener;

use Contao\CoreBundle\Event\GenerateDescriptorEvent;

class GenerateDescriptorListener
{
    private $options;

    public function onDescriptorGeneration(GenerateDescriptorEvent $event): void
    {
        $this->options = $event->getOptions();
        $row = $event->getData();
        $descriptor = null;

        // Get description by defined fields and format
        if (isset($this->options['fields'])) {
            $descriptor = $this->getDescriptorFromFields($row);
        }

        // Fallback: Check for some often used fields
        if ($descriptor === null) {
            $descriptor = $this->getFallbackDescriptor($row);
        }

        $event->setDescriptor($descriptor);
    }

    private function getDescriptorFromFields(array $row): string
    {
        $options = $this->options;
        $fields = $options['fields'];
        $format = (isset($options['format'])) ? $options['format'] : null;

        if (is_string($fields)) {
            $fields = [ $fields ];
        }

        $fields = array_map(function($field) use($row) {
            return $row[$field];
        }, $fields);

        if ($format === null) {
            return implode(', ', $fields);
        }

        return vsprintf($format, $fields);
    }

    private function getFallbackDescriptor(array $row): ?string
    {
        foreach (['title', 'name', 'headline', 'email', 'username'] as $key) {
            if (!empty($row[$key])) {
                return $row[$key];
            }
        }

        return null;
    }
}