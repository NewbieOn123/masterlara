<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Yajra\Datatables\Datatables;
use App\Models\model_groupaccess;
use App\Models\model_menuaccess;
use App\Models\model_roleaccess;

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
        $data = model_groupaccess::with(['role_access', 'users'])->where('aktif', 'Y')->get();
// dd($data);
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('namagroup', function ($data) {
                return  $data->nama_groupaccess;
            })
            ->addColumn('menuakses', function ($data) {
                return  count($data->role_access) . ' ' . 'Menu';
            })
            ->addColumn('pengguna', function ($data) {
                return  count($data->users) . ' ' . 'Pengguna';
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
        // dd($request);
        DB::beginTransaction();

        if ($request->namagroup == null || $request->namagroup == '') {
            DB::rollback();
            $status = ['title' => 'Gagal!', 'status' => 'error', 'message' => 'Nama Group Tidak Boleh Kosong!!'];
            return response()->json($status, 200);
        }

        if ($request->groupmenu == null || $request->groupmenu == '') {
            DB::rollback();
            $status = ['title' => 'Gagal!', 'status' => 'error', 'message' => 'Group Menu Tidak Boleh Kosong!!'];
            return response()->json($status, 200);
        }

        $ceknama = model_groupaccess::where('nama_groupaccess', $request->namagroup)->where('aktif', 'Y')->first();
        if ($ceknama) {
            DB::rollback();
            $status = ['title' => 'Gagal!', 'status' => 'error', 'message' => 'Nama Group Sudah Tersedia, Silahkan Periksa Kembali'];
            return response()->json($status, 200);
        }

        $insert = model_groupaccess::insert([
            'nama_groupaccess' => $request->namagroup,
            'aktif' => 'Y',
            'created_at' => date("Y-m-d H:i:s"),
            'created_by' => Session::get('user')['email']
        ]);

        $getidgroupaccess = model_groupaccess::latest('id_groupaccess')->first();
        foreach ($request->groupmenu as $key => $val) {
            $save = model_roleaccess::insert([
                'idmenu' => $val,
                'idgroupaccess' => $getidgroupaccess->id_groupaccess,
                'aktif' => 'Y',
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => Session::get('user')['email']
            ]);

            if ($save) {
                $sukses[] = "OK";
            } else {
                $gagal[] = "OK";
            }
        }

        if ($insert && empty($gagal)) {
            DB::commit();
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Berhasil Disimpan'];
            return response()->json($status, 200);
        } else {
            DB::rollback();
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
    public function getgroupmenu(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = model_menuaccess::selectRaw(' id_menu, nama_menu');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' (nama_menu like "%' . $search . '%") ');
        }

        $po = $po->where('aktif', 'Y')->orderby('nama_menu', 'asc')->paginate(10, $request->page);
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
        $edidata = model_groupaccess::where('id_groupaccess', $request->id)->first();
        $datarole = model_roleaccess::join('menu', 'menu.id_menu', 'role_access.idmenu')->where('idgroupaccess', $request->id)->get();
        $po = model_menuaccess::selectRaw(' id_menu, nama_menu');
        $datamenu =   $po->where('aktif', 'Y')->orderby('nama_menu', 'asc')->get();

        
        $dataedit = [
            'datagroup' => $edidata,
            'datarole' => $datarole,
            'datamenu'=>$datamenu
        ];

        return response()->json(['status' => 200, 'data' => $dataedit, 'message' => 'Berhasil']);
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
        DB::beginTransaction();
         if ($request->namagroup == null || $request->namagroup == '') {
            DB::rollback();
            $status = ['title' => 'Gagal!', 'status' => 'error', 'message' => 'Nama Group Tidak Boleh Kosong!!'];
            return response()->json($status, 200);
        }

        if ($request->groupmenu == null || $request->groupmenu == '') {
            DB::rollback();
            $status = ['title' => 'Gagal!', 'status' => 'error', 'message' => 'Group Menu Tidak Boleh Kosong!!'];
            return response()->json($status, 200);
        }

        $cekname = model_groupaccess::where('id_groupaccess', '!=', $request->id)->where('nama_groupaccess', $request->namagroup)->first();
        if ($cekname) {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Nama Group Sudah Tersedia, Silahkan Periksa Kembali'];
            return response()->json($status, 200);
        }

        $update = model_groupaccess::where('id_groupaccess', $request->id)->update([
            'nama_groupaccess' => $request->namagroup,
            'aktif' => 'Y',
            'updated_at' => date("Y-m-d H:i:s"),
            'updated_by' => Session::get('user')['email']
        ]);

        //masih blm bisa update or create ketika groupmenu diganti atau ditambah (PR)
        foreach ($request->groupmenu as $key => $val) {
            $save = model_roleaccess::updateOrCreate([
                'idgroupaccess' => $request->id],
                [
                'idmenu' => $val,
                'idgroupaccess' => $request->id,
                'aktif' => 'Y',
                'updated_at' => date("Y-m-d H:i:s"),
                'updated_by' => Session::get('user')['email']
                ]);

            if ($save) {
                $sukses[] = "OK";
            } else {
                $gagal[] = "OK";
            }
        }

        if ($update  && empty($gagal)) {
            DB::commit();
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Berhasil Diubah'];
            return response()->json($status, 200);
        } else {
            DB::rollback();
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
            'aktif' => 'N',
            'updated_at' => date("Y-m-d H:i:s"),
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
}
