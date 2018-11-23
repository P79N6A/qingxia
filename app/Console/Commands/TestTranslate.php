<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stichoza\GoogleTranslate\TranslateClient;

class TestTranslate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:translate {word}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Google Translate';

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
        $tr = new TranslateClient(); // Default is from 'auto' to 'en'
        $tr->setSource('zh-cn'); // Translate from English
        $tr->setTarget('en'); // Translate to Georgian
        $tr->setUrlBase('http://translate.google.cn/translate_a/single'); // Set Google Tr
        $word = $this->argument('word');

        $this->line($word);
        echo $tr->translate($word);
    }
}
