<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExecuteSQLController extends Controller
{
    public function getBestOrderCustomer()
    {
        /**
         * SELECT COUNT(daysorder.CUST_CODE) AS cust_order_count, customer.*
         * FROM daysorder
         *  LEFT JOIN customer
         *  ON customer.CUST_CODE = daysorder.CUST_CODE
         * GROUP BY daysorder.CUST_CODE
         * ORDER BY cust_order_count DESC
         * LIMIT 1
         * */
        return DB::table('daysorder')->selectRaw('count(daysorder.CUST_CODE) as cust_order_count, customer.*')->leftJoin("customer", 'customer.CUST_CODE', '=', "daysorder.CUST_CODE")->groupBy("daysorder.CUST_CODE")->orderBy('cust_order_count', 'desc')->take(1)->get();
    }

    public function countMappingDayOrder()
    {
        /**
         * SELECT MAX(sub_table.count_cust) AS highest_number_buy
         * FROM (
         *  SELECT COUNT(customer.CUST_CODE) AS count_cust
         *  FROM daysorder
         *      LEFT JOIN customer
         *      ON customer.CUST_CODE = daysorder.CUST_CODE
         *  GROUP BY daysorder.CUST_CODE
         * ) AS sub_table;
         */
        return DB::table(function ($subQuery) {
            return $subQuery->from('daysorder')->selectRaw('count(customer.CUST_CODE) as count_cust')->leftJoin('customer', 'customer.CUST_CODE', 'daysorder.CUST_CODE')->groupBy('daysorder.CUST_CODE');
        })->selectRaw('max(count_cust) as highest_number_buy')->get();
    }
}
