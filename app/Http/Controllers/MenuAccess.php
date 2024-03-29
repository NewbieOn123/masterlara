<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\model_menuaccess;

class MenuAccess extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layouts.setting.menu.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        if ($request->namamenu == null || $request->namamenu == '') {
            $status = ['title' => 'Gagal!', 'status' => 'error', 'message' => 'Nama Menu Tidak Boleh Kosong!!'];
            return response()->json($status, 200);
        }
        
        $ceknama = model_menuaccess::where('nama_menu', $request->namamenu)->first();
        if ($ceknama) {
            $status = ['title' => 'Gagal!', 'status' => 'error', 'message' => 'Nama Menu Sudah Tersedia, Silahkan Periksa Kembali'];
            return response()->json($status, 200);
        }

        $insert = model_menuaccess::insert([
            'nama_menu'  => $request->namamenu,
            'aktif'      => 'Y',
            'created_at' => date("Y-m-d H:i:s"),
            'created_by' => Session::get('user')['email']
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
    public function showdatatable()
    {
        $data = model_menuaccess::get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('kodemenu', function ($data) {
                return  $data->id_menu;
            })
            ->addColumn('namamenu', function ($data) {
                if ($data->aktif == 'Y') {
                   $stat = '<i class="fas fa-check-circle text-success"></i>';
                } else {
                   $stat = '<i class="fas fa-times-circle text-danger"></i>';
                }
                
                return  $data->nama_menu . ' ' . $stat;
            })
            ->addColumn('action', function ($data) {
                $button = '';

                    $button .= '<a href="#" data-tooltip="tooltip" data-id="'.$data->id_menu.'" id="editmenu" title="Edit Data" class="btn btn-icon btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
                    $button .= '&nbsp;';

                    if ($data->aktif == 'Y') {
                        $button .= '<a href="#" data-id="'.$data->id_menu.'" id="deletemenu" data-tooltip="tooltip" title="Delete Data" class="btn btn-icon btn-danger btn-sm"><i class="fa fa-trash text-red actiona"></i></a>';
                    } else {
                        $button .= '<a href="#" data-id="'.$data->id_menu.'" id="activemenu" data-tooltip="tooltip" title="Active Data" class="btn btn-icon btn-success btn-sm"><i class="fa fa-plus text-red actiona"></i></a>';
                    }
                    // $button .= '&nbsp;';
                    // $button .= '<a href="#" data-id="'.$data->id.'" id="detailuser" data-tooltip="tooltip" title="Delete Data"><i class="fa fa-info-circle fa-lg text-blue actiona"></i></a>';
                
                return $button;
            })
            ->rawColumns(['namamenu', 'action'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
       $editdata = model_menuaccess::where('id_menu', $request->id)->first();

        return response()->json(['status' => 200, 'data' => $editdata, 'message' => 'Berhasil']);
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
        if ($request->namamenu == null || $request->namamenu == '') {
            $status = ['title' => 'Gagal!', 'status' => 'error', 'message' => 'Nama Menu Tidak Boleh Kosong!!'];
            return response()->json($status, 200);
        }
        
        $cekname = model_menuaccess::where('id_menu', '!=', $request->id)->where('nama_menu', $request->namamenu)->first();
        if ($cekname) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Nama Group Sudah Tersedia, Silahkan Periksa Kembali'];
            return response()->json($status, 200);
        }

        $update = model_menuaccess::where('id_menu', $request->id)->update([
            'nama_menu'  => $request->namamenu,
            'aktif'      => 'Y',
            'updated_at' => date("Y-m-d H:i:s"),
            'updated_by' => Session::get('user')['email']
        ]);

        if ($update) {
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Berhasil Diubah'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Gagas Diubah'];
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
        $delete = model_menuaccess::where('id_menu', $id)->update([
            'aktif'      => 'N',
            'updated_by' => Session::get('user')['email']
        ]);

        if ($delete) {
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Berhasil Dihapus'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Gagal Dihapus'];
            return response()->json($status, 200);
        }
    }

    public function active($id)
    {
        $delete = model_menuaccess::where('id_menu', $id)->update([
            'aktif'      => 'Y',
            'updated_by' => Session::get('user')['email']
        ]);

        if ($delete) {
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Berhasil Diaktifkan'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Gagal Diaktifkan'];
            return response()->json($status, 200);
        }
    }
}
