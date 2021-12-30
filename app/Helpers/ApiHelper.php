<?php

namespace App\Helpers;

class ApiHelper
{


    public static function createSearchParams($model, $searcParams) {
        if(!empty($searcParams)) {

            $searchAble = config('api_search');

            foreach($searcParams as $key => $parameter) {

                if(array_key_exists($key, $searchAble)) {
                    if(!empty($searchAble[$key]['where_type'])) {
                        if($searchAble[$key]['where_type'] == 'like') {
                            $parameter = str_replace(':', $parameter, $searchAble[$key]['operator']);
                            $model = $model->where($key, 'like', $parameter);
                        }
                    } else {
                        if(!empty($searchAble[$key]['operator'])) {
                            $model = $model->where($key, $searchAble[$parameter]['operator'], $parameter);
                        } else {
                            $model = $model->where($key, $parameter);
                        }

                    }
                }
            }
        }

        return $model;
    }

    public static function loadRelations($model, $loadParams) {
        if(!empty($loadParams)) {

            $loadParams = array_keys($loadParams);
            foreach($loadParams as $key) {
               $model = $model->with($key);
            }
        }
        return $model;
    }

    public static function fetchColumns($model, $selectors) {
        if(!empty($selectors)) {

            $keys[] = 'id';
            $selectors = array_keys($selectors);

            foreach($selectors as $key) {
                $keys[] = $key;
            }

            return $model->select($keys);

        } else {
            $model = $model->select('id');
            return $model;
        }
    }

}

