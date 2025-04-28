<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ReportsController extends Controller
{

    public function index(Request $request)
    {
        $query = Report::with('user')->latest();

        if ($request->filled('target_type') && $request->target_type !== 'all') {
            $query->where('target_type', $request->target_type);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(15);

        return view('admin.reports.index', compact('reports'));
    }

    public function show($id)
    {
        $report = Report::with('user')->findOrFail($id);

        $target = null;

        if ($report->target_type === 'product' && $report->reportable_id) {
            $target = Product::find($report->reportable_id);
        } elseif ($report->target_type === 'review' && $report->reportable_id) {
            $target = Review::find($report->reportable_id);
        }elseif ($report->target_type === 'reservation' && $report->reportable_id) {
            $target = Review::find($report->reportable_id);
        }

        return view('admin.reports.show', compact('report', 'target'));
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved',
        ]);

        $report = Report::findOrFail($id);
        $report->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Report status updated successfully.');
    }


    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('admin.reports.index')->with('success', 'Report deleted successfully.');
    }

    public function sendReport(Request $request)
    {
        $validated = $request->validate([
            'reportable_type' => 'required|string',
            'reportable_id' => 'required|integer',
            'target_type' => 'required|in:reservation,product,review,general',
            'message' => 'required|string|min:10',
            'subject' => 'nullable|string|max:255',
        ]);

        Report::create([
            'user_id' => Auth::id(),
            'reportable_type' => $validated['reportable_type'],
            'reportable_id' => $validated['reportable_id'],
            'target_type' => $validated['target_type'],
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully!',
        ]);
    }

    /**
     * Resolve an existing report.
     */
    public function resolveReport(Request $request, $reportId)
{
    $report = Report::findOrFail($reportId);

    if ($report->status === 'pending') {
        $report->status = 'resolved';


        $oldMessage = $report->message ?? '';

        
        $report->message = trim($oldMessage) . ' (Report withdrawn)';

        $report->save();
    }

    return redirect()->back()->with('success', 'Report has been resolved.');
}

}
