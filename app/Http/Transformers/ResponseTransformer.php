<?php

namespace App\Http\Transformers;

use Illuminate\Http\JsonResponse;

class ResponseTransformer
{
    public function toJson($code = null, $message = null, $data=null, $custom_data=null, $seo = null)
    {
        $site_setting = app('request')->get('site_setting');

        $response = [
            'meta' => [
                'code' => $code,
                'message' => $message
            ]
        ];

        $response['seo'] = $seo;

        if(!empty($site_setting)){
            $response['meta']['setting'] = $site_setting;
        }

        if(method_exists($data, 'perPage')) {
            $prevUrl = $data->previousPageUrl();
            $nextUrl = $data->nextPageUrl();
            $perPage = '&per_page=' . $data->perPage();

            $response['data'] = $data->toArray()['data'];
            $response['pagination'] = [
                'total' => $data->total(),
                'per_page' => (int)$data->perPage(),
                'last_page' => $data->lastPage(),
                'next_page_url' => $nextUrl != null ? $nextUrl . $perPage : $nextUrl,
                'prev_page_url' => $prevUrl != null ? $prevUrl . $perPage : $prevUrl,
                'from' => $data->firstItem(),
                'to' => $data->lastItem()
            ];
            
            $data = $response['data'];
        }

        $response['data'] = $custom_data === null ? $data : $custom_data;

        return new JsonResponse($response, $code, []);
    }
}
