<?php
class ezpDbMySQLiClusterGateway extends ezpClusterGateway
{
    public function getDefaultPort()
    {
        return 3306;
    }

    public function connect( $host, $port, $user, $password, $database, $charset = 'utf8' )
    {
        if ( !$this->db = mysqli_connect( $host, $user, $password, $database, $port ) )
            throw new RuntimeException( "Failed connecting to the MySQL database" );

        if ( !mysqli_set_charset( $this->db, $charset ) )
            throw new RuntimeException( "Failed to set database charset to '$charset'");
    }

    public function fetchFileMetadata( $filepath )
    {
        $sql = "SELECT * FROM ezdbfile WHERE name_hash = MD5('$filepath')" ;
        if ( !$res = mysqli_query( $this->db, $sql ) )
            throw new RuntimeException( "Failed to fetch file metadata for '$filepath' " .
                "(error #". mysqli_error( $this->db).": " . mysqli_error( $this->db ) );

        if ( mysqli_num_rows( $res ) == 0 )
            return false;

        $metadata = mysqli_fetch_assoc( $res );
        mysqli_free_result( $res );
        return $metadata;
    }

    public function passthrough( $filepath, $offset = false, $length = false)
    {
        if ( !$res = mysqli_query( $this->db, "SELECT filedata FROM ezdbfile_data WHERE name_hash=MD5('$filepath') ORDER BY offset ASC" ) )
            throw new RuntimeException( "Unable to open file data for '$filepath' " .
                "(error #". mysqli_error( $this->db ).": " . mysqli_error( $this->db ) );
        while ( $row = mysqli_fetch_row( $res ) )
            echo $row[0];
        mysqli_free_result( $res );
    }

    public function close()
    {
        mysqli_close( $this->db );
        unset( $this->db );
    }
}

// return the class name for easier instanciation
return 'ezpDbMySQLiClusterGateway';