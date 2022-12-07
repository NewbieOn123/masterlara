<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Hash;
use App\Models\User;
use App\Models\model_groupaccess;

class UserAccess extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('layouts.setting.useraccess.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = User::join('group_access', 'group_access.id_groupaccess', 'users.role_access_group')->where('group_access.aktif', 'Y')->where('users.active', 'Y')->get();
// dd($data);
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('namauser', function ($data) {
                return  $data->name;
            })
            ->addColumn('emailuser', function ($data) {
                return  $data->email;
            })
            ->addColumn('groupakses', function ($data) {
                return  $data->nama_groupaccess;
            })
            ->addColumn('action', function ($data) {
                $button = '';

                    $button .= '<a href="#" data-tooltip="tooltip" data-id="'.$data->id.'" id="edituser" title="Edit Data" class="btn btn-icon btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
                    $button .= '&nbsp;';
                    $button .= '<a href="#" data-id="'.$data->id.'" id="deleteuser" data-tooltip="tooltip" title="Delete Data" class="btn btn-icon btn-danger btn-sm"><i class="fa fa-trash text-red actiona"></i></a>';
                    // $button .= '&nbsp;';
                    // $button .= '<a href="#" data-id="'.$data->id.'" id="detailuser" data-tooltip="tooltip" title="Delete Data"><i class="fa fa-info-circle fa-lg text-blue actiona"></i></a>';
                
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);

        //password default
        $pass = Hash::make('password123');

        $cekname = User::where('name', $request->nameuser)->first();
        if ($cekname) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'User Nama Sudah Tersedia, Silahkan Cek Kembali'];
            return response()->json($status, 200);
        }

        $cekemail = User::where('email', $request->emailuser)->first();
        if ($cekemail) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'User Email Sudah Tersedia, Silahkan Cek Kembali'];
            return response()->json($status, 200);
        }

        $insert = User::insert([
        	'name' => $request->nameuser,
        	'email' => $request->emailuser,
            'role_access_group' => $request->groupakses,
        	'password' => $pass,
        	'active' => 'Y',
        	'created_at' => date("Y-m-d H:i:s")
        ]);

        if ($insert) {
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Berhasil Disimpan'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Gagal Disimpan'];
            return response()->json($status, 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getgroupaccess(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = model_groupaccess::selectRaw(' id_groupaccess, nama_groupaccess');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' (nama_groupaccess like "%' . $search . '%") ');
        }

        $po = $po->where('aktif', 'Y')->orderby('nama_groupaccess', 'asc')->paginate(10, $request->page);
        // dd($po);
        return response()->json($po);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        // dd($request);

        $edidata = User::join('group_access', 'group_access.id_groupaccess', 'users.role_access_group')->where('users.id', $request->id)->where('users.active', 'Y')->where('group_access.aktif', 'Y')->first();
        
        return response()->json(['status' => 200, 'data' => $edidata, 'message' => 'Berhasil']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd($request);

        $cekname = User::where('id', '!=', $request->id)->where('name', $request->nameuser)->first();
        if ($cekname) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'User Nama Sudah Tersedia, Silahkan Cek Kembali'];
            return response()->json($status, 200);
        }

        $cekemail = User::where('id', '!=', $request->id)->where('email', $request->emailuser)->first();
        if ($cekemail) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'User Email Sudah Tersedia, Silahkan Cek Kembali'];
            return response()->json($status, 200);
        }

        $update = User::where('id', $request->id)->update([
        	'name' => $request->nameuser,
        	'email' => $request->emailuser,
            'role_access_group' => $request->groupakses,
        	'active' => 'Y',
        	'updated_at' => date("Y-m-d H:i:s")
        ]);

        if ($update) {
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Berhasil Diubah'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Gagal Diubah'];
            return response()->json($status, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);

        $delete = User::where('id', $id)->update([
        	'active' => 'N'
        ]);

        if ($delete) {
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Berhasil Dihapus'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Gagal Dihapus'];
            return response()->json($status, 200);
        }
    }
}