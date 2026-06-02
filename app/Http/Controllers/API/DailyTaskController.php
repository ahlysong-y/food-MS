<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyTask;
use Carbon\Carbon;

class DailyTaskController extends Controller
{
    // មុខងារសម្រាប់ ទាញយកបញ្ជីការងារដែលមិនទាន់បានធ្វើ (Pending Tasks)
    public function getPendingTasks(Request $request)
    {
        $user = $request->user(); // ចាប់យកបុគ្គលិកដែលកំពុង Login

        // ទាញយកកិច្ចការប្រចាំសាខារបស់គាត់ ដែលមិនទាន់បានធ្វើ
        $tasks = DailyTask::where('branch_id', $user->branch_id)
            ->where('status', 'Pending')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $tasks
        ], 200);
    }

    // មុខងារសម្រាប់ បញ្ជាក់ថាការងារបានធ្វើរួច (Mark as Completed)
    public function completeTask(Request $request, $id)
    {
        $task = DailyTask::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'រកមិនឃើញទិន្នន័យការងារនេះទេ!'
            ], 404);
        }

        if ($task->status === 'Completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'ការងារនេះត្រូវបានបញ្ជាក់រួចរាល់ម្តងហើយ!'
            ], 400);
        }

        // ធ្វើបច្ចុប្បន្នភាពទិន្នន័យ
        $task->update([
            'status' => 'Completed',
            'completed_at' => Carbon::now(), // កត់ត្រាម៉ោងដែលបានធ្វើរួច
            'user_id' => $request->user()->id // កត់ត្រាថាបុគ្គលិកណាជាអ្នកចុចធ្វើរួច
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'អបអរសាទរ! កិច្ចការរបស់អ្នកត្រូវបានកត់ត្រាថាបានបញ្ចប់។',
            'data' => $task
        ], 200);
    }
}
