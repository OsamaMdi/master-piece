<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Report;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;



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

        // إذا تم حل المشكلة والمرسل هو تاجر، نرسل له إشعار
        if ($request->status === 'resolved' && $report->user && $report->user->user_type === 'merchant') {
            NotificationService::send(
                $report->user->id,
                'Your submitted report has been marked as resolved. Thank you for your feedback.',
                'report_resolved',
                url('/merchant/reports/' . $report->id), // لو عندك صفحة عرض تقارير للتاجر
                'normal',
                auth()->id()
            );
        }

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

        // 1. Create the report
        $report = Report::create([
            'user_id' => Auth::id(),
            'reportable_type' => $validated['reportable_type'],
            'reportable_id' => $validated['reportable_id'],
            'target_type' => $validated['target_type'],
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        // 2. Build message & URL
        $reportType = ucfirst($validated['target_type']);
        $reportMessage = "A new {$reportType} report has been submitted by " . auth()->user()->name;
        $reportUrl = url('/admin/reports/' . $report->id);

        // 3. Send notification to all admins
        $admins = User::where('user_type', 'admin')->get();

        foreach ($admins as $admin) {
            NotificationService::send(
                $admin->id,
                $reportMessage,
                'report',
                $reportUrl,
                'important',
                auth()->id()
            );
        }

        // 4. Return JSON response
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


public function myReports(Request $request)
{
    $reports = Report::where('user_id', auth()->id())
        ->when(in_array($request->target_type, ['reservation', 'review']), function ($query) use ($request) {
            $query->where('target_type', $request->target_type);
        })
        ->latest()
        ->paginate(10);

    return view('merchants.reports', compact('reports'));
}


}
