<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Reference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $logs = $user->logs()->orderBy('log_date', 'desc')->get();

        $logDates = $logs->pluck('log_date')->map(fn($d) => $d->format('Y-m-d'))->toArray();
        $logsByDate = $logs->keyBy(fn($log) => $log->log_date->format('Y-m-d'));

        $selectedMonth = $request->query('month', date('m'));
        $selectedYear = $request->query('year', date('Y'));

        return view('logs', [
            'logs' => $logs,
            'logDates' => $logDates,
            'logsByDate' => $logsByDate,
            'selectedMonth' => (int) $selectedMonth,
            'selectedYear' => (int) $selectedYear,
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'log_day' => ['required', 'integer', 'min:1', 'max:365'],
            'log_date' => ['required', 'date'],
            'log_location' => ['nullable', 'string', 'max:255'],
            'log_place' => ['nullable', 'string', 'max:255'],
            'log_summary' => ['required', 'string'],
            'log_knowledge' => ['nullable', 'string'],
            'log_tools' => ['nullable', 'string'],
            'log_note' => ['nullable', 'string'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        $log = Log::create([
            'user_id' => auth()->id(),
            'log_day' => $validated['log_day'],
            'log_date' => $validated['log_date'],
            'log_location' => $validated['log_location'] ?? null,
            'log_place' => $validated['log_place'] ?? null,
            'log_summary' => $validated['log_summary'],
            'log_knowledge' => $validated['log_knowledge'] ?? null,
            'log_tools' => $validated['log_tools'] ?? null,
            'log_note' => $validated['log_note'] ?? null,
            'log_status' => 'completed',
            'log_created_at' => now(),
            'log_updated_at' => now(),
        ]);

        if ($request->hasFile('images')) {
            $userId = auth()->id();
            foreach ($request->file('images') as $index => $image) {
                $filename = 'logs/' . $userId . '/' . $log->log_id . '/' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                $path = Storage::disk('s3')->putFileAs('', $image, $filename, 'public');

                Reference::create([
                    'log_id' => $log->log_id,
                    'reference_image' => $path,
                    'reference_created_at' => now(),
                    'reference_status' => 'active',
                ]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'id' => $log->log_id]);
        }

        return redirect()->back()->with('success', 'Log entry saved successfully!');
    }

    public function show($id)
    {
        $log = Log::where('log_id', $id)->where('user_id', auth()->id())->first();

        if (!$log) {
            return response()->json(['success' => false, 'error' => 'Log not found'], 404);
        }

        return response()->json([
            'success' => true,
            'log' => [
                'log_date' => $log->log_date->format('Y-m-d'),
                'log_day' => $log->log_day,
                'log_location' => $log->log_location,
                'log_place' => $log->log_place,
                'log_summary' => $log->log_summary,
                'log_knowledge' => $log->log_knowledge,
                'log_tools' => $log->log_tools,
                'log_note' => $log->log_note,
            ]
        ]);
    }

    public function edit($date)
    {
        $log = Log::where('log_date', $date)->where('user_id', auth()->id())->first();

        if (!$log) {
            return response()->json(['success' => false, 'error' => 'Log not found'], 404);
        }

        return response()->json([
            'success' => true,
            'log' => [
                'log_date' => $log->log_date->format('Y-m-d'),
                'log_day' => $log->log_day,
                'log_location' => $log->log_location,
                'log_place' => $log->log_place,
                'log_summary' => $log->log_summary,
                'log_knowledge' => $log->log_knowledge,
                'log_tools' => $log->log_tools,
                'log_note' => $log->log_note,
            ]
        ]);
    }

    public function printByDate($date)
    {
        $user = auth()->user();
        $log = $user->logs()->where('log_date', $date)->first();

        if (!$log) {
            return redirect()->route('logs.index')->with('error', 'Log not found');
        }

        return view('print', [
            'user' => $user,
            'logs' => collect([$log]),
            'defaultSettings' => $user->defaults,
            'today' => $log->log_date->format('d/m/Y'),
        ]);
    }

    public function print()
    {
        $user = auth()->user();
        $logs = $user->logs()->orderBy('log_date', 'desc')->get();
        $defaultSettings = $user->defaults;

        return view('print', [
            'user' => $user,
            'logs' => $logs,
            'defaultSettings' => $defaultSettings,
            'today' => date('d/m/Y'),
        ]);
    }

    public function update(Request $request, $date)
    {
        $log = Log::where('log_date', $date)->where('user_id', auth()->id())->first();

        if (!$log) {
            return response()->json(['success' => false, 'error' => 'Log not found'], 404);
        }

        $validated = $request->validate([
            'log_day' => ['required', 'integer', 'min:1', 'max:365'],
            'log_summary' => ['required', 'string'],
            'log_location' => ['nullable', 'string', 'max:255'],
            'log_place' => ['nullable', 'string', 'max:255'],
            'log_knowledge' => ['nullable', 'string'],
            'log_tools' => ['nullable', 'string'],
            'log_note' => ['nullable', 'string'],
        ]);

        $log->update([
            'log_day' => $validated['log_day'],
            'log_summary' => $validated['log_summary'],
            'log_location' => $validated['log_location'] ?? null,
            'log_place' => $validated['log_place'] ?? null,
            'log_knowledge' => $validated['log_knowledge'] ?? null,
            'log_tools' => $validated['log_tools'] ?? null,
            'log_note' => $validated['log_note'] ?? null,
            'log_updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Log updated successfully', 'log_id' => $log->log_id]);
    }
}