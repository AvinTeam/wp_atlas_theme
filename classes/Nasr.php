<?php

class NasrDB
{

    private $wpdb;
    private $tablename;

    public function __construct()
    {
        global $wpdb;

        $this->wpdb = $wpdb;
        $this->tablename = $wpdb->prefix . 'atlas_row';

    }

    public function insert(array $data, array $format): int | false
    {

        foreach ($data as $key => $value) {
            $data[ $key ] = $value;
        }

        $data[ 'created_at' ] = current_time('mysql');
        $format[  ] = '%s';

        $inserted = $this->wpdb->insert(
            $this->tablename,
            $data,
            $format

        );

        return ($inserted) ? $this->wpdb->insert_id : false;

    }

    public function num(string $mobile = '', string $status = ''): int | string
    {

        $where = "";

        if (!empty($mobile)) {
            $where .= " AND mobile ='$mobile' ";
        }
        if (empty($status)) {
            $where .= " AND status !='sms' ";
        } elseif (!empty($status) && $status=="all") {
            $where .= "";
        } elseif (!empty($status)) {
            $where .= " AND status ='$status' ";
        }

        $num = $this->wpdb->get_var("SELECT COUNT(*) FROM $this->tablename WHERE 1=1  $where ");

        return absint($num);

    }

    public function select(int $per_page, int $offset, string $status = '', string $date = ''): array | object | null
    {
        $sqlwhere = '';

        if (empty($status)) {
            $sqlwhere .= " AND status !='sms' ";
        } elseif (!empty($status)) {
            $sqlwhere .= " AND status ='$status' ";
        }

        if (!empty($date)) {
            $sqlwhere .= " AND created_at <= '$date' ";

        }

        $mpn_row = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM %i WHERE 1=1  $sqlwhere ORDER BY `created_at` DESC LIMIT %d OFFSET %d",
                [ $this->tablename, $per_page, $offset ]
            ), ARRAY_A
        );
        return $mpn_row;

    }

    public function update(array $data, array $where, array $format = null, array $where_format = null): int | false
    {

        $result = false;

        if ($data && $where) {

            $result = $this->wpdb->update(
                $this->tablename,
                $data,
                $where,
                $format,
                $where_format
            );
        }
        return $result;

    }

    public function get($key, $value): object | array | false
    {
        $result = false;
        if ($value) {
            global $wpdb;

            $result = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM %i WHERE %i = %s",
                    [ $this->tablename, $key, $value ]
                )
            );
        }
        return $result;
    }

    public function delete($row_id): int | false
    {
        $result = false;
        if ($row_id) {

            $result = $this->wpdb->delete(
                $this->tablename,
                [ 'ID' => $row_id ],
                [ '%d' ]

            );

        }

        return $result;

    }

    public function update_type()
    {
        $mpn_row = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "UPDATE %i SET type = 'failed' WHERE type = 'progress' AND created_at <= NOW() - INTERVAL 30 MINUTE",
                [ $this->tablename ]
            )
        );

        return $mpn_row;

    }

}
