<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Ticket;
use Notification;
use App\Notifications\TicketStatus;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) 
        {
            $data = Ticket::where('user_id',auth()->user()->id)->get();
 
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
   
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-status="'.$row->status.'" data-original-title="Edit" class="edit btn btn-info btn-sm editTicket">Change Status</a>';    
                        return $btn;
                    })
                    ->editColumn('created_at', function ($row) {
                       return [
                          'display' => e($row->created_at->format('m/d/Y')),
                          'timestamp' => $row->created_at->timestamp
                       ];
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('tickets/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $ticket = new Ticket;
        $ticket->title = $request->title;
        $ticket->description = $request->description;
        $ticket->user_id = auth()->user()->id;
        $ticket->save();
        return redirect()->route('tickets.index')->with('success','Ticket has been created successfully.');
    }

    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $ticket = Ticket::find($id);
        $ticket->status = $request->status;
        $ticket->save();
        $user = auth()->user();

        //Send notification to user
        $ticket = Ticket::find($id);
        $data['name'] = $ticket->title;
        $data['status'] = $ticket->status;
        Notification::send($user, new TicketStatus($data));

        return response()->json(['success'=>'Status Update successfully.']);
    }

    public function markNotification(Request $request)
    {
        auth()->user()
            ->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();
 
        return response()->noContent();
    }
}
