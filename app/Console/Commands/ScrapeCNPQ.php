<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Crawlers\CnpqCrawler;
use App\Crawlers\CnpqService;
use App\Bidding;
use App\File as BiddingFile;
use Storage;
use File;
use Eloquent;
use DB;

class ScrapeCNPQ extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:cnpq {--pages=1} {--allPages} {--reset} {--importFile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CNPQ Crawler/Scraper';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('reset')) {
            Eloquent::unguard();
            
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                Bidding::truncate();
                BiddingFile::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            File::deleteDirectory(public_path('storage/documents/', true));
        }

        $totalPages = $this->option('allPages') ? -1 : $this->option('pages');
        $crawler = new CnpqCrawler(env('CNPQ_HOST'));
        $crawler->setTotalPages($totalPages);

        $cnpqService = new CnpqService();
        $response    = $crawler->crawlerData();
        $totalPages  = $crawler->getPages();

        $bar = $this->output->createProgressBar($totalPages*10);
        foreach($response as $data) {
            $bidding = $cnpqService->createBidding($data);
            if($bidding) { $bar->advance(); }
            
            if($data['files'] && $bidding) {
                foreach($data['files'] as $file) {
                   $cnpqService->createBiddingFiles($file, $bidding, $this->option('importFile'));
                }
            }
        }
        
        $bar->finish();

    }

}
