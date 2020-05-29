<?php

namespace Coco\SourceWatcher\Core\Database\Connections;

use Coco\SourceWatcher\Core\Row;
use Coco\SourceWatcher\Core\SourceWatcherException;
use Coco\SourceWatcher\Utils\i18n;
use Doctrine\DBAL\DBALException;

/**
 * Class EmbeddedDatabaseConnector
 * @package Coco\SourceWatcher\Core\Database\Connections
 */
abstract class EmbeddedDatabaseConnector extends Connector
{
    /**
     * @param Row $row
     * @return int
     * @throws SourceWatcherException
     */
    public function insert ( Row $row ) : int
    {
        if ( $this->tableName == null || $this->tableName == "" ) {
            throw new SourceWatcherException( i18n::getInstance()->getText( EmbeddedDatabaseConnector::class, "No_Table_Name_Found" ) );
        }

        $connection = $this->connect();

        // For some reason, for embedded databases such as SQLite, the DBAL Connection object will return false when asked if isConnected.

        try {
            $numberOfAffectedRows = $connection->insert( $this->tableName, $row->getAttributes() );

            $connection->close();
        } catch ( DBALException $dbalException ) {
            $errorMessage = sprintf( "Something went wrong while trying to insert the row: %s", $dbalException->getMessage() );
            throw new SourceWatcherException( $errorMessage );
        }

        return $numberOfAffectedRows;
    }
}
