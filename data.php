<?php
$connection = pg_connect("host='localhost' port='5432' user='postgres' password='12345678' dbname='db_tbprakgis'");

$sql = "SELECT
          gid,
          nama,
          ST_AsGeoJSON(geom) AS geometry,
          ST_Y(ST_CENTROID(geom)) AS lat,
          ST_X(ST_CENTROID(geom)) AS lng
        FROM tugas3_region
        ";
$geojson = array(
    'type'      => 'FeatureCollection',
    'features'  => array()
);
$query = pg_query($connection, $sql);

if ($query === false) {
    echo pg_last_error($connection); // Display error message
} else {
    if (pg_num_rows($query) > 0) {
        while ($row = pg_fetch_assoc($query)) {
            $feature = array(
                "type" => 'Feature',
                'geometry' => json_decode($row['geometry'], true),
                'properties' => array(
                    'gid' => $row['gid'],
                    'nama' => $row['nama'],
                    'center' => array(
                        'lat' => $row['lat'],
                        'lng' => $row['lng']
                    )
                )
            );
            array_push($geojson['features'], $feature);
        }
        echo json_encode($geojson);
    } else {
        echo "No rows found.";
    }
}

pg_close($connection);
?>
