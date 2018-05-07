<?php

namespace App\Crawlers;

use App\Crawlers\CnpqCrawler;
use App\Bidding;
use App\File;
use Storage;
use GuzzleHttp;

class CnpqService
{
    const SOURCE = 'CNPQ';

    public function createBidding($data)
    {
        $bidding = Bidding::firstOrCreate([
            'title' => $data['title'],
            'object' => $data['object'],
            'date_open' => $data['date_open'],
            'source' => self::SOURCE
        ]);

        return $bidding;
    }

    public function createBiddingFiles($file, $bidding, $importFile = false)
    {
        $path = $file['file'];
        
        if($importFile && $this->validFile($file['file'])) {
            $path = '/storage'.$this->importFile($file['file']);
        }

        $file = File::firstOrCreate([
            'label' => $file['label'],
            'file' => $path,
            'bidding_id' => $bidding->id
        ]);
    }

    protected function importFile($urlFile)
    {
        $pdf = file_get_contents($urlFile);
        $parsed_url = parse_url($urlFile);
        $path = implode('/', explode('/', $parsed_url['path'], -1));
        $path = str_replace(' ', '_',urldecode($path));

        Storage::disk('public')->put($path, $pdf);

        return $path;
    }

    protected function validFile($file)
    {
        $handle = curl_init($file);

        //return the transfer as a string 
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1); 
        
        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        curl_close($handle);

        if($response && $httpCode != 404) {
            return true;
        }

        return false;
    }


}