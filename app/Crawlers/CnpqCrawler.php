<?php

namespace App\Crawlers;

use Goutte;

class CnpqCrawler 
{
    protected $host;

    protected $totalPages = 1;

    public function __construct($host) 
    {
        $this->host = $host;
    }

    public function crawlerData() 
    {
        $parsed_url = parse_url($this->host);
        $totalPages = $this->getPages();
        $registros  = $totalPages * 10;
        $results    = [];

        for($page = 1; $page <= $totalPages; $page++) {
            $actualPage = $this->host."?p_p_id=licitacoescnpqportlet_WAR_licitacoescnpqportlet_INSTANCE_BHfsvMBDwU0V&p_p_lifecycle=0&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=2&pagina={$page}&delta=10&registros={$registros}";

            $crawler = Goutte::request('GET', $actualPage);
            $results = array_merge($results, $crawler->filter('.resultado-licitacao .licitacoes')->each(function ($node) use ($parsed_url) {              
                $titulo = $node->filter('.titLicitacao ')->text(); 
                $data_abertura = $node->filter('.data_licitacao span')->eq(0)->text();
                $data_publicacao = $node->filter('.data_licitacao span')->eq(1)->text();
                $objeto = $node->filter('.cont_licitacoes p')->each(function ($node) { return $node->text(); });
                $objeto = implode(' ', array_filter($objeto));
                
                $files = $node->filter('.download-list li')->each(function ($nodeI) use ($parsed_url) {
                    $file = parse_url($nodeI->filter('a')->attr('href'));
                    $file = count($file) == 1 ? 'http://'.$parsed_url['host'].$file['path'] : $nodeI->filter('a')->attr('href');
                    return [
                        'label' => $nodeI->filter('a')->text(), 
                        'file' => $file
                    ];
                });
                
                return [
                    'title' => $titulo, 
                    'object' => $objeto,
                    'date_open' => $data_abertura,
                    'files' => $files
                ];
            }));
        }

        return $results;
    }

    public function getPages()
    {        
        $totalPages = $this->totalPages;

        if($totalPages == -1) {
            $crawler = Goutte::request('GET', $this->host);
            
            $lastPageBtn  = $crawler->filter('#formLicit .last a')->attr('onclick');
            $lastPageData = explode("'", $lastPageBtn);
           
            $totalPages = $lastPageData > 1 ? $lastPageData[1] : 1;
        }
        
        return $totalPages;
    }
    
    public function setTotalPages($totalPages) 
    {
        $this->totalPages = $totalPages;
    }

}