<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\model_groupaccess;

class GroupAccess extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layouts.setting.groupaccess.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        $data = model_groupaccess::where('aktif', 'Y')->get();
// dd($data);
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('namagroup', function ($data) {
                return  $data->nama_groupaccess;
            })
            ->addColumn('menuakses', function ($data) {
                return  '0' . ' ' . 'Menu';
            })
            ->addColumn('pengguna', function ($data) {
                return  '0' . ' ' . 'Pengguna';
            })
            ->addColumn('action', function ($data) {
                $button = '';

                    $button .= '<a href="#" data-tooltip="tooltip" data-id="'.$data->id_groupaccess.'" id="editgroup" title="Edit Data" class="btn btn-icon btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
                    $button .= '&nbsp;';
                    $button .= '<a href="#" data-id="'.$data->id_groupaccess.'" id="deletegroup" data-tooltip="tooltip" title="Delete Data" class="btn btn-icon btn-danger btn-sm"><i class="fa fa-trash text-red actiona"></i></a>';
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
        
        $ceknama = model_groupaccess::where('nama_groupaccess', $request->namagroup)->where('aktif', 'Y')->first();
        if ($ceknama) {
            $status = ['title' => 'Gagal!', 'status' => 'error', 'message' => 'Nama Group Sudah Tersedia, Silahkan Periksa Kembali'];
            return response()->json($status, 200);
        }

        $insert = model_groupaccess::insert([
            'nama_groupaccess' => $request->namagroup,
            'aktif' => 'Y',
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $edidata = model_groupaccess::where('id_groupaccess', $request->id)->first();

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
        $cekname = model_groupaccess::where('id_groupaccess', '!=', $request->id)->where('nama_groupaccess', $request->namagroup)->first();
        if ($cekname) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Nama Group Sudah Tersedia, Silahkan Periksa Kembali'];
            return response()->json($status, 200);
        }

        $update = model_groupaccess::where('id_groupaccess', $request->id)->update([
            'nama_groupaccess' => $request->namagroup,
            'aktif' => 'Y',
            'updated_at' => date("Y-m-d H:i:s")
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
        $delete = model_groupaccess::where('id_groupaccess', $id)->update([
            'aktif' => 'N'
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
