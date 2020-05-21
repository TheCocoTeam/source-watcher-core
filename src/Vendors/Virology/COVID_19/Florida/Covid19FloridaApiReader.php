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
    /**
     * @var string
     */
    private string $floridaCOVID19CasesURL = "https://services1.arcgis.com/CY1LXxl9zlJeBuRZ/arcgis/rest/services/Florida_COVID19_Cases/FeatureServer/0/query?";

    /**
     * @var string
     */
    private string $genericQueryParameters = 'f=json&where=1=1&returnGeometry=false&spatialRel=esriSpatialRelIntersects&outFields=*&outStatistics=[{"statisticType":"sum","onStatisticField":"%s","outStatisticFieldName":"value"}]&cacheHint=true';

    /**
     * @var array|string[]
     */
    private array $statisticFields = [ "T_positive" => "Total Positive Cases",

        "T_NegRes" => "Total Negative Florida Residents", "T_NegNotFLRes " => "Total Negative Non-Florida Residents",

        "T_total" => "Total Number Of Tests",

        "Deaths" => "Deaths",

        "C_FLRes" => "Positive Florida Residents", "C_FLResOut" => "Positive Non-Florida Residents",

        "C_HospYes_Res" => "Hospitalizations Florida Residents", "C_HospYes_NonRes" => "Hospitalizations Non-Florida Residents" ];

    /**
     * Covid19FloridaApiReader constructor.
     */
    public function __construct ()
    {
        parent::__construct();
    }

    /**
     * @param string $url
     * @return string
     * @throws SourceWatcherException
     */
    public function getResultFromURL ( string $url ) : string
    {
        $this->endpoint = $url;

        $result = "0";

        try {
            $jsonResult = json_decode( parent::read(), true );

            if ( $jsonResult != null ) {
                $result = $jsonResult["features"][0]["attributes"]["value"];
            }
        } catch ( SourceWatcherException $exception ) {
            $errorMessage = sprintf( "Something went wrong while trying to get the result from the URL: %s", $exception->getMessage() );
            throw new SourceWatcherException( $errorMessage );
        } catch ( Exception $exception ) {
            $errorMessage = sprintf( "Something unexpected went wrong while trying to get the result from the URL: %s", $exception->getMessage() );
            throw new SourceWatcherException( $errorMessage );
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getResults () : array
    {
        $results = [];

        foreach ( $this->statisticFields as $parameter => $description ) {
            $queryParameters = sprintf( $this->genericQueryParameters, $parameter );

            try {
                $results[$parameter] = [ "description" => $description, "value" => $this->getResultFromURL( "{$this->floridaCOVID19CasesURL}{$queryParameters}" ) ];
            } catch ( SourceWatcherException $exception ) {
                $results[$parameter] = [ "description" => sprintf( "Field %s couldn't be retrieved", $parameter ), "value" => "" ];
            }
        }

        return $results;
    }
}
