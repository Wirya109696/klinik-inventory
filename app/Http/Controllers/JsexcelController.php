<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class JsexcelController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users-index', ['only' => ['index']]);
        $this->middleware('permission:users-store', ['only' => ['create', 'store']]);
        $this->middleware('permission:users-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users-erase', ['only' => ['destroy']]);
    }
    public function index(){

        $data['css'] = array(
            '/lib/datatables/dataTables.bootstrap.min.css',
			'/lib/select/component-chosen.min.css',
            '/lib/select/bootstrap-chosen.css',
        );

        $data['js'] = array(
            '/lib/datatables/datatables.min.js',
            '/lib/datatables/dataTables.bootstrap5.min.js',
            '/lib/select/chosen.jquery.min.js',
            '/js/karyawanjs/karyawan.js'

        );
        return view('excel.index', [
            'title'  => 'Excel',
            'header' => '<i class="bi bi-people"></i>&nbsp;<b>Data Jexcel</b>',
            'data'   => $data
        ]);

    }

    public function TabelKaryawanFix()
    {
        $get_all = Karyawan::all();

        $data = array();
        $i = 1;
        foreach ($get_all as $row) {

            $data[] = array(
                $row->name,
                $row->email,
                $row->sup_addres,
                $row->id,

            );
        }
        echo json_encode($data);
    }

    public function karyawanUpdate(Request $request)
    {
    $data = json_decode($request->input('data'));

    $updatedData = [];

    if (!empty($data) && is_array($data)) {
        $errors = [];

        foreach ($data as $row) {
            // Validate required fields
            if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
                $errors[] = 'Name, Email, and sup_addres are required for each entry.';
                continue; // Skip processing this row
            }

            // Validate uniqueness
            $existingRecord = DB::table('karyawans')->where('email', $row[1])->first();
            if ($existingRecord && $existingRecord->id != $row[3]) {
                $errors[] = 'Email must be unique. Duplicate email found for ' . $row[1] . '.';
                continue; // Skip processing this row
            }

            if ($row[3] != '') {
                $detailrow = [
                    'name' => $row[0],
                    'email' => $row[1],
                    'sup_addres' => $row[2],
                ];

                DB::table('karyawans')->where('id', $row[3])->update($detailrow);
            } else {
                $detailrow = [
                    'name' => $row[0],
                    'email' => $row[1],
                    'sup_addres' => $row[2],
                ];

                DB::table('karyawans')->insert($detailrow);
            }

            // Store data for each updated row
            $updatedData[] = $detailrow;
        }

        if (!empty($errors)) {
            $notif['notif'] = implode(' ', $errors);
            $notif['status'] = false;

            return response()->json($notif);
        }

        $notif['notif'] = 'Update Data Success!';
        $notif['status'] = true;
        $notif['data'] = $updatedData;

        return response()->json($notif);
    } else {
        $notif['notif'] = 'Data is not found!';
        $notif['status'] = false;

        return response()->json($notif);
    }
}


    public function karyawanDelete(Request $request){
        $idsToDelete = $request->input('idsToDelete');

        if (!empty($idsToDelete) && is_array($idsToDelete)) {
            // Perform the deletion based on IDs
            DB::table('karyawans')->whereIn('id', $idsToDelete)->delete();

            $notif['notif'] = 'Delete Data Success!';
            $notif['status'] = true;

            return response()->json($notif);
        } else {
            $notif['notif'] = 'No IDs provided for deletion.';
            $notif['status'] = false;

            return response()->json($notif);
        }
    }
}
