<?php

namespace Coco\SourceWatcher\Vendors\Virology\COVID_19\Florida;

use Coco\SourceWatcher\Core\Api\ApiReader;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Exception;

/**
 * Class Covid19FloridaApiReader
 *
 * Layer: Florida_COVID_Cases (ID:0)
 * https://services1.arcgis.com/CY1LXxl9zlJeBuRZ/ArcGIS/rest/services/Florida_COVID19_Cases/FeatureServer/0
 *
 * @package Coco\SourceWatcher\Vendors\Virology\COVID_19\Florida
 */
class Covid19FloridaApiReader extends ApiReader
{
    public static string $VALUE_INDEX = "value";

    private string $floridaCOVID19CasesURL = "https://services1.arcgis.com/CY1LXxl9zlJeBuRZ/arcgis/rest/services/Florida_COVID19_Cases/FeatureServer/0/query?";

    private string $genericQueryParameters = 'f=json&where=1=1&returnGeometry=false&spatialRel=esriSpatialRelIntersects&outFields=*&outStatistics=[{"statisticType":"sum","onStatisticField":"%s","outStatisticFieldName":"value"}]&cacheHint=true';

    private array $statisticFields = [
        "T_positive" => "Total Positive Cases",

        "T_NegRes" => "Total Negative Florida Residents",
        "T_NegNotFLRes" => "Total Negative Non-Florida Residents",

        "T_total" => "Total Number Of Tests",

        "Deaths" => "Deaths",

        "C_FLRes" => "Positive Florida Residents",
        "C_FLResOut" => "Positive Non-Florida Residents",

        "C_HospYes_Res" => "Hospitalizations Florida Residents",
        "C_HospYes_NonRes" => "Hospitalizations Non-Florida Residents"
    ];

    public function getFloridaCOVID19CasesURL () : string
    {
        return $this->floridaCOVID19CasesURL;
    }

    public function setFloridaCOVID19CasesURL ( string $floridaCOVID19CasesURL ) : void
    {
        $this->floridaCOVID19CasesURL = $floridaCOVID19CasesURL;
    }

    public function getGenericQueryParameters () : string
    {
        return $this->genericQueryParameters;
    }

    public function setGenericQueryParameters ( string $genericQueryParameters ) : void
    {
        $this->genericQueryParameters = $genericQueryParameters;
    }

    public function getStatisticFields ()
    {
        return $this->statisticFields;
    }

    public function setStatisticFields ( array $statisticFields ) : void
    {
        $this->statisticFields = $statisticFields;
    }

    /**
     * @param string $url
     * @return string
     * @throws SourceWatcherException
     */
    public function getResultFromURL ( string $url ) : string
    {
        $this->resourceURL = $url;

        $result = "0";

        try {
            $jsonResult = json_decode( parent::read(), true );

            if ( $jsonResult != null ) {
                $result = $jsonResult["features"][0]["attributes"][Covid19FloridaApiReader::$VALUE_INDEX];
            }
        } catch ( Exception $exception ) {
            $errorMessage = sprintf( "Something unexpected went wrong while trying to get the result from the URL", 0,
                $exception->getMessage() );
            throw new SourceWatcherException( $errorMessage );
        }

        return $result;
    }

    public function getResults () : array
    {
        $results = [];

        foreach ( $this->statisticFields as $parameter => $description ) {
            $queryParameters = sprintf( $this->genericQueryParameters, $parameter );

            try {
                $results[$parameter] = [
                    "description" => $description,
                    Covid19FloridaApiReader::$VALUE_INDEX => $this->getResultFromURL( "{$this->floridaCOVID19CasesURL}{$queryParameters}" )
                ];
            } catch ( SourceWatcherException $exception ) {
                $results[$parameter] = [
                    "description" => sprintf( "Field %s couldn't be retrieved", $parameter ),
                    Covid19FloridaApiReader::$VALUE_INDEX => ""
                ];
            }
        }

        return $results;
    }
}
