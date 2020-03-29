<?php

function add_missing_dates($data)
{
    $result = [];
    foreach ($data as $k => $item) {
        $d = new DateTime(date_format(date_create($item['addedon']), 'd M Y'));
        $result[] = $item;
        if (isset($data[$k + 1])) {
            $diff = (new DateTime(date_format(date_create($data[$k + 1]['addedon']), 'd M Y')))->diff($d)->days;
            if ($diff > 1) {
                $result = array_merge($result, array_map(function ($v) use ($d) {
                    $d_copy = clone $d;
                    return [
                        "id" => "nil",
                        "cust_id" => "Nil",
                        "machine_id" => "nil",
                        "station_id" => "nil",
                        "machine_location" => "nil",
                        "new_enrollment" => "nil",
                        "mandatory_update" => "nil",
                        "normal_update" => "nil",
                        'addedon' => $d_copy->add(new DateInterval('P' . $v . 'D'))->format('Y-m-d'),
                        'updatedon' => $d_copy->add(new DateInterval('P' . $v . 'D'))->format('Y-m-d'),
                        "total" => "nil"
                    ];
                }, range(1, $diff - 1)));
            }
        }
    }

    return $result;
}

function add_total_field($data)
{
    $totalMend = 0;
    $totalNew = 0;
    $totalNormal = 0;
    $grossTotal = 0;
    foreach ($data as $key => $value) {
        $mend = $value['mandatory_update'];
        $new = $value['new_enrollment'];
        $normal = $value['normal_update'];
        $dayTotal = $mend + $new + $normal;
        $totalMend = $totalMend + $mend;
        $totalNew = $totalNew + $new;
        $totalNormal = $totalNormal + $normal;
        $grossTotal = $grossTotal + $dayTotal;
        $data[$key]['total'] = $dayTotal;
    }
    return (array(
        'data' => $data,
        'totalMend' => $totalMend,
        'totalNew' => $totalNew,
        'totalNormal' => $totalNormal,
        'grossTotal' => $grossTotal,
    ));
}
