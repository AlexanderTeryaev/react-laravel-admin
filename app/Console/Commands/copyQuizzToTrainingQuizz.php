<?php

namespace App\Console\Commands;

use App\Quizz;
use App\ShopQuestion;
use App\ShopQuizz;
use Illuminate\Console\Command;

class copyQuizzToTrainingQuizz extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copyquizztotrainingquizz {quizz_id} {training_quizz_id} {update_quizz_details?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy quizz questions to training quizz';

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
        $quizz = Quizz::find($this->argument('quizz_id'));
        $training_quizz = ShopQuizz::find($this->argument('training_quizz_id'));

        if (!$quizz)
            $this->error('Quizz #'. $this->argument('quizz_id') .' not found');

        if (!$training_quizz)
            $this->error('Quizz #'. $this->argument('training_quizz_id') .' not found');

        if ($this->argument('update_quizz_details') == "true")
            $training_quizz->update([
                'name' => $quizz->name,
                'image_url' => $quizz->image_url,
                'description' => $quizz->description,
                'difficulty' => $quizz->difficulty,
                'tags' => $quizz->tags
            ]);

        $i = 0;
        foreach ($quizz->questions as $question)
        {
            ShopQuestion::create([
                'shop_training_id' => $training_quizz->shop_training_id,
                'shop_quizz_id' => $training_quizz->id,
                'shop_author_id' => $training_quizz->shop_author_id,
                'question' => $question->question,
                'good_answer' => $question->good_answer,
                'bad_answer' =>  $question->bad_answer,
                'bg_url' => $question->bg_url,
                'difficulty' => $question->difficulty,
                'more' => $question->more,
            ]);
            $i++;
        }
        $this->info("Done! $i questions copied!");
    }
}
