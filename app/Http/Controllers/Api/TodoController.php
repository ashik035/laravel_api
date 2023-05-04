<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;

class TodoController extends Controller
{  
    public function index()
    {
        try {   
            $todos = Todo::with(['tasks' => function ($query) {
                $query->select('task_name', 'todo_id')->orderBy('id');
            }])
            ->select('id', 'name')
            ->get();  

            $todosWithTaskNames = $todos->map(function ($todo) {
                $taskNames = $todo->tasks->pluck('task_name');
                return ['id' => $todo->id, 'name' => $todo->name, 'tasks' => $taskNames];
            });

            return response()->json($todosWithTaskNames, 200);
            
        } catch (\Exception $e) { 
            return response()->json(['error' => $e->getMessage()], 500);
        }  
    }

    public function store(Request $request)
    { 
        try {  
            $id = Auth::id();
            $tasks = [];
            $validatedData = [];
    
            $validate = Validator::make($request->all(), [
                'name' => 'required|string|min:2|max:255',
                'tasks' => 'required|string|min:2|max:255', 
            ]);        
    
            if ($validate->fails())
            {
                return response(['errors'=>$validate->errors()->all()], 422);
            }
    
            $validatedData['name'] = $request['name'];
            $validatedData['tasks'] = $request['tasks'];
            $validatedData['user_id'] = $id;
            $task_names = explode(',', $validatedData['tasks']);
            $todo = Todo::create($validatedData);
    
            $names = '';
            
            foreach ($task_names as $key => $task_name) {
                $task = [];
                $task['task_name'] = $task_name; 
                $task['todo_id'] = $todo->id;
                
                $names .= $task['task_name'] . ', ';
                array_push($tasks, $task);
            } 
            DB::table('tasks')->insert($tasks);
            
            $names = rtrim($names, ', ');
            $todo['tasks'] =  $names;
            return response()->json($todo, 201);      

        } catch (\Exception $e) { 
            return response()->json(['error' => $e->getMessage()], 500);
        } 
    }

    public function show($id)
    { 
        try {
            $todo = Todo::with(['tasks' => function ($query) {
                $query->select('task_name', 'todo_id')->orderBy('id');
            }])
            ->select('id', 'name')
            ->find($id); 
            $taskNames = $todo->tasks->pluck('task_name');
            return response()->json(['id' => $todo->id, 'name' => $todo->name, 'tasks' => $taskNames], 200);
            
        } catch (\Exception $e) { 
            return response()->json(['error' => $e->getMessage()], 500);
        } 
    }

    public function update(Request $request, $id)
    {  
        try {   
            $validatedData = [];
            $validate = Validator::make($request->all(), [
                'name' => 'required|string|min:2|max:255',
                'tasks' => 'required|string|min:2|max:255', 
            ]);        

            if ($validate->fails())
            {
                return response(['errors'=>$validate->errors()->all()], 422);
            }
            
            $validatedData['name'] = $request['name'];
            $validatedData['tasks'] = $request['tasks'];
        
            $todo = Todo::findOrFail($id);
        
            $todo->name = $validatedData['name'];
            $todo->save();
        
            $task_names = explode(',', $validatedData['tasks']);
            $tasks = [];
        
            foreach ($task_names as $key => $task_name) {
                $task = [];
                $task['task_name'] = $task_name;
                $task['todo_id'] = $todo->id;
                array_push($tasks, $task);
            }
        
            $todo->tasks()->delete();
            $todo->tasks()->createMany($tasks);
        
            $todo = $todo->fresh('tasks');
        
            return response()->json($todo, 200);

        } catch (\Exception $e) { 
            return response()->json(['error' => $e->getMessage()], 500);
        } 
    }

    public function destroy($id)
    { 
        try {   
            $todo = Todo::findOrFail($id);
            $del = $todo->delete(); 
            return response()->json('Todo id '.$todo->id.' is successfully Deleted', 204);

        } catch (\Exception $e) { 
            return response()->json(['error' => 'No item found to delete of id '.$id], 500);
        } 
    }
}
