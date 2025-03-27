<?php
namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\UrlGen;
use OpenGraph;   

class InterviewTipsController extends Controller
{
    /**
     * Display the interview tips page.
     */
    public function index()
    {
        // Define the interview tips
        $tips = [
            'Research the company and the role before the interview.',
            'Practice common interview questions.',
            'Dress appropriately for the interview.',
            'Be confident but humble.',
            'Listen carefully and ask insightful questions.'
        ];

        // Open Graph (OG) data for social media sharing
        // $og = OpenGraph::property('og:title', 'Interview Tips')
        //               ->property('og:description', 'Helpful tips to ace your next interview.')
        //               ->property('og:image', url('path/to/image.jpg'))
        //               ->property('og:url', url()->current());

        // Pass the data to the view
        return view('interviewTips.index');
    }
}
