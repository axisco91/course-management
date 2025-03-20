<?php

namespace App\Console\Commands;

use App\Helpers\CourseStatusHelper;
use App\Models\Course;
use App\Models\CourseStatus;
use App\Models\TrainingContractElement;
use Illuminate\Console\Command;

class updateElementTutor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateElementTutor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update course status';

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
     * @return int
     */
    public function handle()
    {

        $trainingContractElements = TrainingContractElement::all();

        foreach ($trainingContractElements as $trainingContractElement){

        }



        return 0;
    }
}
