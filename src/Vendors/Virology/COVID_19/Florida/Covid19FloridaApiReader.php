<?php

namespace Coco\SourceWatcher\Vendors\Virology\COVID_19\Florida;

use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Core\Api\ApiReader;

/**
 * Class Covid19FloridaApiReader
 *
 * Layer: Florida_COVID_Cases (ID:0)
 * https://services1.arcgis.com/CY1LXxl9zlJeBuRZ/ArcGIS/rest/services/Florida_COVID19_Cases/FeatureServer/0
 *
 * Layer: Florida_COVID19_Case_Line_Data (ID:0)
 * https://services1.arcgis.com/CY1LXxl9zlJeBuRZ/arcgis/rest/services/Florida_COVID19_Case_Line_Data/FeatureServer/0/
 *
 * @package Coco\SourceWatcher\Vendors\Virology\COVID_19\Florida
 */
class Covid19FloridaApiReader extends ApiReader
{
    /**
     * @var string
     */
    private string $floridaCOVID19Cases = "https://services1.arcgis.com/CY1LXxl9zlJeBuRZ/arcgis/rest/services/Florida_COVID19_Cases/FeatureServer/0/query?";

    /**
     * @var string
     */
    private string $floridaCOVID19CaseLineData = "https://services1.arcgis.com/CY1LXxl9zlJeBuRZ/arcgis/rest/services/Florida_COVID19_Case_Line_Data/FeatureServer/0/query?";

    /**
     * @var string
     */
    private string $totalPositiveCasesParameters = 'f=json&where=1=1&returnGeometry=false&spatialRel=esriSpatialRelIntersects&outFields=*&outStatistics=[{"statisticType":"sum","onStatisticField":"T_positive","outStatisticFieldName":"value"}]&cacheHint=true';

    /**
     * @var string
     */
    private string $totalNegativeResidentsParameters = 'f=json&where=1=1&returnGeometry=false&spatialRel=esriSpatialRelIntersects&outFields=*&outStatistics=[{"statisticType":"sum","onStatisticField":"T_NegRes","outStatisticFieldName":"value"}]&cacheHint=true';

    /**
     * @var string
     */
    private string $totalTestsParameters = 'f=json&where=1=1&returnGeometry=false&spatialRel=esriSpatialRelIntersects&outFields=*&outStatistics=[{"statisticType":"sum","onStatisticField":"T_total","outStatisticFieldName":"value"}]&cacheHint=true';

    /**
     * @var string
     */
    private string $deathsParameters = 'f=json&where=1=1&returnGeometry=false&spatialRel=esriSpatialRelIntersects&outFields=*&outStatistics=[{"statisticType":"sum","onStatisticField":"Deaths","outStatisticFieldName":"value"}]&cacheHint=true';

    /**
     * @var string
     */
    private string $flResParameters = 'f=json&where=1=1&returnGeometry=false&spatialRel=esriSpatialRelIntersects&outFields=*&outStatistics=[{"statisticType":"sum","onStatisticField":"C_FLRes","outStatisticFieldName":"value"}]&cacheHint=true';

    /**
     * @var string
     */
    private string $flResOutParameters = 'f=json&where=1=1&returnGeometry=false&spatialRel=esriSpatialRelIntersects&outFields=*&outStatistics=[{"statisticType":"sum","onStatisticField":"C_FLResOut","outStatisticFieldName":"value"}]&cacheHint=true';

    /**
     * @var string
     */
    private string $hospitalizedParameters = '';

    /**
     * Covid19FloridaApiReader constructor.
     */
    public function __construct ()
    {
        parent::__construct();

        $this->hospitalizedParameters = 'f=json&where=' . urlencode( "Hospitalized='Yes' AND Jurisdiction='FL resident'" ) . '&returnGeometry=false&spatialRel=esriSpatialRelIntersects&outFields=*&outStatistics=[{"statisticType":"count","onStatisticField":"ObjectId","outStatisticFieldName":"value"}]&cacheHint=true';
    }

    /**
     *
     */
    public function getTotalPositiveCases () : string
    {
        $this->endpoint = $this->floridaCOVID19Cases . $this->totalPositiveCasesParameters;

        $totalPositiveCases = "0";

        try {
            $jsonResult = json_decode( parent::read(), true );

            if ( $jsonResult != null ) {
                $totalPositiveCases = $jsonResult["features"][0]["attributes"]["value"];
            }
        } catch ( SourceWatcherException $exception ) {

        } catch ( Exception $exception ) {

        }

        return $totalPositiveCases;
    }

    /**
     * @return string
     */
    public function getTotalNegativeResidents () : string
    {
        $this->endpoint = $this->floridaCOVID19Cases . $this->totalNegativeResidentsParameters;

        $totalNegativeResidents = "0";

        try {
            $jsonResult = json_decode( parent::read(), true );

            if ( $jsonResult != null ) {
                $totalNegativeResidents = $jsonResult["features"][0]["attributes"]["value"];
            }
        } catch ( SourceWatcherException $exception ) {

        } catch ( Exception $exception ) {

        }

        return $totalNegativeResidents;
    }

    /**
     * @return string
     */
    public function getTotalTests () : string
    {
        $this->endpoint = $this->floridaCOVID19Cases . $this->totalTestsParameters;

        $totalTests = "0";

        try {
            $jsonResult = json_decode( parent::read(), true );

            if ( $jsonResult != null ) {
                $totalTests = $jsonResult["features"][0]["attributes"]["value"];
            }
        } catch ( SourceWatcherException $exception ) {

        } catch ( Exception $exception ) {

        }

        return $totalTests;
    }

    /**
     * @return string
     */
    public function getDeaths () : string
    {
        $this->endpoint = $this->floridaCOVID19Cases . $this->deathsParameters;

        $deaths = "0";

        try {
            $jsonResult = json_decode( parent::read(), true );

            if ( $jsonResult != null ) {
                $deaths = $jsonResult["features"][0]["attributes"]["value"];
            }
        } catch ( SourceWatcherException $exception ) {

        } catch ( Exception $exception ) {

        }

        return $deaths;
    }

    /**
     * @return string
     */
    public function getPositiveResidents () : string
    {
        // Get the C_FLRes result

        $this->endpoint = $this->floridaCOVID19Cases . $this->flResParameters;

        $flResParameters = "0";

        try {
            $jsonResult = json_decode( parent::read(), true );

            if ( $jsonResult != null ) {
                $flResParameters = $jsonResult["features"][0]["attributes"]["value"];
            }
        } catch ( SourceWatcherException $exception ) {

        } catch ( Exception $exception ) {

        }

        // Get the C_FLResOut result

        $this->endpoint = $this->floridaCOVID19Cases . $this->flResOutParameters;

        $flResOutParameters = "0";

        try {
            $jsonResult = json_decode( parent::read(), true );

            if ( $jsonResult != null ) {
                $flResOutParameters = $jsonResult["features"][0]["attributes"]["value"];
            }
        } catch ( SourceWatcherException $exception ) {

        } catch ( Exception $exception ) {

        }

        return intval( $flResParameters ) + intval( $flResOutParameters );
    }

    /**
     * @return string
     */
    public function getHospitalized () : string
    {
        $this->endpoint = $this->floridaCOVID19CaseLineData . $this->hospitalizedParameters;

        $hospitalized = "0";

        try {
            $jsonResult = json_decode( parent::read(), true );

            if ( $jsonResult != null ) {
                $hospitalized = $jsonResult["features"][0]["attributes"]["value"];
            }
        } catch ( SourceWatcherException $exception ) {

        } catch ( Exception $exception ) {

        }

        return $hospitalized;
    }
}
