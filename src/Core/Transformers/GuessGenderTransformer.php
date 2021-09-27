<?php

namespace Coco\SourceWatcher\Core\Transformers;

use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\Transformer;

use GenderDetector\GenderDetector;

/**
 *
 */
class GuessGenderTransformer extends Transformer
{
    /**
     * @var string
     */
    protected string $country;

    /**
     * @var string
     */
    protected string $firstNameField;

    /**
     * @var string
     */
    protected string $genderField;

    /**
     * @var array|string[]
     */
    protected array $availableOptions = [ "country", "firstNameField", "genderField" ];

    /**
     * @param Row $row
     */
    public function transform ( Row $row )
    {
        $firstName = $row->get($this->firstNameField);
        echo "firstName = " . $firstName . PHP_EOL;

        $currentGender = $row->get($this->genderField);
        echo "currentGender = " . $currentGender . PHP_EOL;

        if (empty($currentGender)) {
            $genderDetector = new GenderDetector();

            $gender = $genderDetector->detect($firstName, $this->country);
            echo "gender = " . $gender . PHP_EOL;

            $row->set( $this->genderField, $gender );
        }
    }
}
