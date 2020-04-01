<?php
namespace App\Http\Libs;

class Helper
{
    public static function getFilter($request)
    {
        $filter = new \stdClass();
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $filter->ip = $ip;

        // Custom filter.
        $filter->filter = (object)$request->input('filter');

        // Global filter.
        $filter->filterTexts = preg_split('/(-|\/)/', $request->input('search')['value']);

        // Columns.
        $columns = $request->input('columns') == null ? array() : $request->input('columns');
        
        // Filter columns.
        $filter->filterColumns = array();
        $filterColumns = array_filter($columns, function ($v, $k){
            return !empty($v) && $v['searchable'] && !empty($v['search']);
        }, ARRAY_FILTER_USE_BOTH);
        foreach ($filterColumns as $value){
            $filterColumn = new \stdClass();
            $filterColumn->field = $value['data'];
            if (empty($filterColumn->field)) continue;

            $filterColumn->value = $value['search']['value'];
            if ($filterColumn->value === '') continue;
            array_push($filter->filterColumns, $filterColumn);
        }
        
        // Sort columns.
        $filter->sortColumns = array();
        $orderColumns = $request->input('order') != null ? $request->input('order') : array();
        foreach ($orderColumns as $value){
            $sortColumn = new \stdClass();
            $sortColumn->field = $columns[$value['column']]['data'];
            if (empty($sortColumn->field)) continue;
            
            $sortColumn->dir = $value['dir'];
            array_push($filter->sortColumns, $sortColumn);
        }
        
        // Paging.
        $filter->pageLimit = $request->input('length') ?: 1;
        $filter->pageOffset = $request->input('start') ?: 0;
        
        // Log::info(json_encode($filter));
        return $filter;
    }
}
?>