<?php

namespace App\Console\Commands;

use App\Jobs\GenerateFacultyPersonnelPdfJob;
use App\Jobs\GenerateFacultyReportJob;
use Illuminate\Console\Command;

class GenerateEvaluationReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uiscoursesurvey:generate-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $report = $this->choice(
            'What type of report do you want to generate?',
            ['faculty', 'faculty-personnel'],
            $multiple = false
        );
        match ($report) {
            'faculty' => GenerateFacultyReportJob::dispatchSync(),
            'faculty-personnel' => GenerateFacultyPersonnelPdfJob::dispatchSync(),
        };
    }
}
