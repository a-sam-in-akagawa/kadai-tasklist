<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

use App\Models\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->get();
            
        }
        
        return view('dashboard',['tasks' => $tasks,]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::check()) {
            $task = new Task;

            // メッセージ作成ビューを表示
            return view('tasks.create', [
                'task' => $task,
            ]);
        }
        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::check()) {
            $request->validate([
                'content' => 'required',
                'status'=> 'required|max:10'
            ]);
        
            $request->user()->tasks()->create([
                'content' => $request->content,
                'status' => $request->status,
            ]);
        /*$task = new Task;
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();*/
        }
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (\Auth::check()) {
            $task = Task::findOrFail($id);

            if (\Auth::id() === $task->user_id) {
                return view('tasks.show', ['task' => $task,]);
            }
        }
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::check()) {
            $task = Task::findOrFail($id);
            if (\Auth::id() === $task->user_id) {
                return view('tasks.edit', ['task' => $task,]);
            }
        }
        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (\Auth::check()) {
            $request->validate([
                'content'=> 'required',
                'status'=> 'required|max:10',
            ]);
            $task = Task::findOrFail($id);
            if (\Auth::id() === $task->user_id) {
                $task->content = $request->content;
                $task->status = $request->status;
                $task->save();
            }
            
        }
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Auth::check()) {
            $task = Task::findOrFail($id);
        
            $task->delete();
        
            return redirect('/');
        }
        return view('dashboard');
    }
}
