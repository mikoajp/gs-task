<?php

namespace App\Http\Controllers;

use App\Services\MessageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ReportController extends Controller
{
    private string $inspectionsFile = 'inspections.json';

    private string $failuresFile = 'failure_reports.json';

    public function dashboard()
    {
        $stats = [
            'inspections_count' => 0,
            'failures_count' => 0,
            'last_updated' => null,
            'has_data' => false,
        ];

        if (File::exists($this->inspectionsFile)) {
            $inspections = json_decode(File::get($this->inspectionsFile), true);
            $stats['inspections_count'] = count($inspections);
            $stats['has_data'] = true;
            $stats['last_updated'] = File::lastModified($this->inspectionsFile);
        }

        if (File::exists($this->failuresFile)) {
            $failures = json_decode(File::get($this->failuresFile), true);
            $stats['failures_count'] = count($failures);
            $stats['has_data'] = true;
        }

        return view('dashboard', compact('stats'));
    }

    public function process(Request $request, MessageProcessor $processor)
    {
        $request->validate([
            'source_file' => 'required|file|mimes:json|max:2048',
        ]);

        $file = $request->file('source_file');
        $json = json_decode(File::get($file->getRealPath()), true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($json)) {
            return back()->withErrors(['source_file' => 'Invalid JSON format.']);
        }

        $result = $processor->process($json);

        // Save files (sharing state with CLI)
        File::put($this->inspectionsFile, json_encode($result['inspections'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        File::put($this->failuresFile, json_encode($result['failure_reports'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return redirect()->route('dashboard')
            ->with('success', 'Processed '.count($json).' messages successfully.');
    }

    public function inspections()
    {
        $data = [];
        if (File::exists($this->inspectionsFile)) {
            $data = json_decode(File::get($this->inspectionsFile), true);
        }

        return view('inspections', ['inspections' => $data]);
    }

    public function failures()
    {
        $data = [];
        if (File::exists($this->failuresFile)) {
            $data = json_decode(File::get($this->failuresFile), true);
        }

        return view('failures', ['failures' => $data]);
    }
}
